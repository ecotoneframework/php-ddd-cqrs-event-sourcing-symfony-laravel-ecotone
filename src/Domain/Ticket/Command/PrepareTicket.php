<?php

namespace App\Domain\Ticket\Command;

class PrepareTicket
{
    private string $ticketType;
    private string $description;

    public function __construct(string $ticketType, string $description)
    {
        $this->ticketType = $ticketType;
        $this->description = $description;
    }

    public function getTicketType(): string
    {
        return $this->ticketType;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}