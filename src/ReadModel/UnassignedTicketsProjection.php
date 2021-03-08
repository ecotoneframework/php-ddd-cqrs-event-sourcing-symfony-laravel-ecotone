<?php

namespace App\ReadModel;

use App\Domain\Ticket\Event\TicketWasAssigned;
use App\Domain\Ticket\Event\TicketWasCancelled;
use App\Domain\Ticket\Event\TicketWasPrepared;
use App\Domain\Ticket\Ticket;
use Doctrine\DBAL\Connection;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionInitialization;
use Ecotone\EventSourcing\Attribute\ProjectionReset;
use Ecotone\Messaging\Attribute\Parameter\Header;
use Ecotone\Messaging\MessageHeaders;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;

#[Projection("unassigned_tickets", Ticket::class)]
class UnassignedTicketsProjection
{
    const TABLE_NAME = "unassigned_tickets";
    const GET_UNASSIGED_TICKETS = "getUnassigedTickets";

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    #[QueryHandler(self::GET_UNASSIGED_TICKETS)]
    public function getUnassignedTickets() : array
    {
        return $this->connection->executeQuery(<<<SQL
    SELECT * FROM unassigned_tickets
SQL)->fetchAllAssociative();
    }

    #[EventHandler]
    public function onTicketWasPreperad(TicketWasPrepared $event) : void
    {
        $this->connection->insert(self::TABLE_NAME, [
            "ticket_id" => $event->getTicketId(),
            "ticket_type" => $event->getTicketType(),
            "description" => $event->getDescription()
        ]);
    }

    #[EventHandler]
    public function onTicketWasCancelled(TicketWasCancelled $event) : void
    {
        $this->connection->delete(self::TABLE_NAME, ["ticket_id" => $event->getTicketId()]);
    }

    #[EventHandler]
    public function onTicketWasAssigned(TicketWasAssigned $event) : void
    {
        $this->connection->delete(self::TABLE_NAME, ["ticket_id" => $event->getTicketId()]);
    }

    #[ProjectionInitialization]
    public function initialize(): void
    {
        $this->connection->executeStatement(<<<SQL
        CREATE TABLE IF NOT EXISTS unassigned_tickets (
            ticket_id UUID PRIMARY KEY,
            ticket_type VARCHAR(255),
            description VARCHAR(50)
        )
    SQL
        );
    }

    #[ProjectionReset]
    public function reset(): void
    {
        $this->connection->executeStatement(<<<SQL
        DELETE FROM unassigned_tickets
    SQL
        );
    }
}