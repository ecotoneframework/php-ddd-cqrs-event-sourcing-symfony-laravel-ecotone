<?php

namespace App\Domain\Ticket\Command;

final readonly class AssignTicket
{
    public string $ticketId;
    public string $assignTo;
}