<?php

namespace App\Domain\Ticket\Command;

class PrepareTicket
{
    public readonly ?string $ticketId;
    public readonly string $ticketType;
    public readonly string $description;
}