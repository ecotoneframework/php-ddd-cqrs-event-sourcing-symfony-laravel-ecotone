<?php

namespace App\Domain\Ticket\Event;

class TicketWasPrepared
{
    private string $ticketId;
    private string $ticketType;
    private string $description;

    public function __construct(string $ticketId, string $ticketType, string $description)
    {
        $this->ticketId = $ticketId;
        $this->ticketType = $ticketType;
        $this->description = $description;
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
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