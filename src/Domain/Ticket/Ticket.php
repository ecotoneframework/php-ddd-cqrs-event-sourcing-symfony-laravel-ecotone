<?php

namespace App\Domain\Ticket;

use App\Domain\Ticket\Command\AssignTicket;
use App\Domain\Ticket\Command\PrepareTicket;
use App\Domain\Ticket\Event\TicketWasAssigned;
use App\Domain\Ticket\Event\TicketWasCancelled;
use App\Domain\Ticket\Event\TicketWasPrepared;
use Ecotone\Modelling\Attribute\AggregateFactory;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcedAggregate;
use Ecotone\Modelling\WithAggregateVersioning;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

#[EventSourcedAggregate]
class Ticket
{
    const PREPARE_TICKET_TICKET = "ticket.prepareTicket";
    const CANCEL_TICKET         = "ticket.cancel";
    const ASSIGN_TICKET = "ticket.assign";

    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private string $ticketId;
    private bool $isCancelled;
    private bool $isAssigned;

    private function __construct()
    {
    }

    #[CommandHandler(self::PREPARE_TICKET_TICKET)]
    public static function prepare(PrepareTicket $command): array
    {
        return [new TicketWasPrepared(Uuid::uuid4()->toString(), $command->getTicketType(), $command->getDescription())];
    }

    #[CommandHandler(self::CANCEL_TICKET)]
    public function cancel(): array
    {
        if ($this->isCancelled) {
            return [];
        }

        return [new TicketWasCancelled($this->ticketId)];
    }

    #[CommandHandler(self::ASSIGN_TICKET)]
    public function assignTo(AssignTicket $command): array
    {
        if ($this->isAssigned) {
            return [];
        }
        if ($this->isCancelled) {
            throw new InvalidArgumentException("Can't assign {$command->getAssignedTo()} as this ticket has assignation");
        }

        return [new TicketWasAssigned($this->ticketId, $command->getAssignedTo())];
    }

    #[AggregateFactory]
    public static function rebuild(
        array $events
    ): static
    {
        $ticket = new static();
        foreach ($events as $event) {
            $ticket = match (get_class($event)) {
                TicketWasPrepared::class => $ticket->applyTicketWasPrepared($event, $ticket),
                TicketWasCancelled::class => $ticket->applyTicketWasCancelled($event, $ticket),
                TicketWasAssigned::class => $ticket->applyTicketWasAssigned($event, $ticket)
            };
        }

        return $ticket;
    }

    private function applyTicketWasPrepared(TicketWasPrepared $event, self $ticket): static
    {
        $ticket->ticketId    = $event->getTicketId();
        $ticket->isCancelled = false;
        $ticket->isAssigned  = false;

        return $ticket;
    }

    private function applyTicketWasCancelled(TicketWasCancelled $event, self $ticket): static
    {
        $ticket->isCancelled = true;

        return $ticket;
    }

    private function applyTicketWasAssigned(TicketWasAssigned $event, self $ticket): static
    {
        $ticket->isAssigned = true;

        return $ticket;
    }
}