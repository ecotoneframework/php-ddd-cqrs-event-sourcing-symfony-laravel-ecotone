<?php

namespace App\ReadModel;

use App\Domain\Ticket\Event\TicketWasAssigned;
use App\Domain\Ticket\Event\TicketWasCancelled;
use App\Domain\Ticket\Event\TicketWasPrepared;
use App\Domain\Ticket\Ticket;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionInitialization;
use Ecotone\EventSourcing\Attribute\ProjectionReset;
use Ecotone\EventSourcing\EventStore;
use Ecotone\Messaging\Attribute\Asynchronous;
use Ecotone\Messaging\Attribute\Parameter\Header;
use Ecotone\Messaging\MessageHeaders;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;

#[Asynchronous(ReadModelConfiguration::ASYNCHRONOUS_PROJECTIONS_CHANNEL)]
#[Projection("last_prepared_tickets", Ticket::class)]
class LastPreparedTicketsProjection
{
    const TABLE_NAME = "last_prepared_tickets";
    const GET_PREPARED_TICKETS = "getPreparedTickets";
    const GET_TICKET_DETAILS = "getTicketDetails";

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    #[QueryHandler(self::GET_TICKET_DETAILS)]
    public function getTicket(string $ticketId) : array
    {
        return [
            "ticket" => $this->connection->executeQuery(<<<SQL
    SELECT * FROM last_prepared_tickets WHERE ticket_id = :ticket_id
SQL, ["ticket_id" => $ticketId])->fetchAllAssociative()[0]
       ];
    }

    #[QueryHandler(self::GET_PREPARED_TICKETS)]
    public function getPreparedTickets() : array
    {
        return $this->connection->executeQuery(<<<SQL
SELECT * FROM last_prepared_tickets ORDER BY prepared_at DESC
SQL
        )->fetchAllAssociative();
    }

    #[EventHandler(endpointId:"LastPreparedTicketsProjection::onTicketWasPreperad")]
    public function onTicketWasPrepared(TicketWasPrepared $event, #[Header(MessageHeaders::TIMESTAMP)] $occurredOn) : void
    {
        $this->connection->insert(self::TABLE_NAME, [
            "ticket_id" => $event->getTicketId(),
            "ticket_type" => $event->getTicketType(),
            "description" => $event->getDescription(),
            "status" => "awaiting",
            "prepared_at" => date('Y-m-d H:i:s', $occurredOn)
        ]);
    }

    #[EventHandler(endpointId:"LastPreparedTicketsProjection::onTicketWasCancelled")]
    public function onTicketWasCancelled(TicketWasCancelled $event) : void
    {
        $this->connection->update(self::TABLE_NAME, ["status" => "cancelled"], ["ticket_id" => $event->getTicketId()]);
    }

    #[EventHandler(endpointId:"LastPreparedTicketsProjection::onTicketWasAssigned")]
    public function onTicketWasAssigned(TicketWasAssigned $event) : void
    {
        $this->connection->update(self::TABLE_NAME, ["status" => "assigned", "assigned_to" => $event->getAssignedTo()], ["ticket_id" => $event->getTicketId()]);
    }

    #[ProjectionInitialization]
    public function initializeProjection() : void
    {
            $this->connection->executeStatement(<<<SQL
        CREATE TABLE IF NOT EXISTS last_prepared_tickets (
            ticket_id UUID PRIMARY KEY,
            ticket_type VARCHAR(255),
            description TEXT,
            status VARCHAR(50),
            assigned_to VARCHAR(255),
            prepared_at TIMESTAMP
        )
    SQL);
    }

    #[ProjectionReset]
    public function resetProjection() : void
    {
        $this->connection->executeStatement(<<<SQL
    DELETE FROM last_prepared_tickets
SQL);
    }
}