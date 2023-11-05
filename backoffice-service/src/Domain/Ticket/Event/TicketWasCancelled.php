<?php

namespace App\Domain\Ticket\Event;

final readonly class TicketWasCancelled
{
    public function __construct(
        public string $ticketId
    )
    {}
}