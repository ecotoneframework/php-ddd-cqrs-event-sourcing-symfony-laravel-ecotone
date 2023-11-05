<?php

namespace App\Domain\Ticket\Command;

final readonly class PrepareTicket
{
    public function __construct(
        public ?string $ticketId = null,
        public string $ticketType,
        public string $description
    )
    {}
}