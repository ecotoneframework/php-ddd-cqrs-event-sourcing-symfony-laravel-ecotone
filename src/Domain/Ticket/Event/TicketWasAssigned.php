<?php


namespace App\Domain\Ticket\Event;


class TicketWasAssigned
{
    private string $ticketId;
    private string $assignedTo;

    public function __construct(string $ticketId, string $assignedTo)
    {
        $this->ticketId = $ticketId;
        $this->assignedTo = $assignedTo;
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    public function getAssignedTo(): string
    {
        return $this->assignedTo;
    }
}