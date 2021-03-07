<?php


namespace App\Domain\Ticket\Command;


class AssignTicket
{
    private string $ticketId;
    private string $assignTo;

    public function __construct(string $ticketId, string $assignTo)
    {
        $this->ticketId = $ticketId;
        $this->assignTo = $assignTo;
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    public function getAssignedTo(): string
    {
        return $this->assignTo;
    }
}