<?php

namespace App\Domain\Ticket\Event;

final readonly class TicketWasAssigned
{
    public function __construct(
        public string $ticketId,
        public string $assignedTo
    )
    {
    }
}