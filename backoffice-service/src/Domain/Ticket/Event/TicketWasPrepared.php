<?php

namespace App\Domain\Ticket\Event;

final readonly class TicketWasPrepared
{
    public function __construct(
        public string $ticketId,
        public string $ticketType,
        public string $description
    )
    {
    }
}