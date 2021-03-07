<?php

namespace App\UI\Controller;

use App\Domain\Ticket\Command\AssignTicket;
use App\Domain\Ticket\Command\CancelTicket;
use App\Domain\Ticket\Command\PrepareTicket;
use App\Domain\Ticket\Command\ReleaseTicket;
use Ecotone\Modelling\CommandBus;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketApiController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    #[Route("/tickets", methods:["POST"])]
    public function prepare(Request $request) : Response
    {
        $this->commandBus->send(new PrepareTicket(
            $request->get("ticketType"),
            $request->get("description")
        ));

        return new RedirectResponse("/");
    }

    #[Route("/tickets/{ticketId}/cancel", methods:["POST"])]
    public function cancel(Request $request) : Response
    {
        $ticketId = $request->get("ticketId");
        $this->commandBus->send(new CancelTicket($ticketId));

        return new RedirectResponse("/tickets/" . $ticketId);
    }

    #[Route("/tickets/{ticketId}/assign", methods:["POST"])]
    public function assign(Request $request) : Response
    {
        $ticketId = $request->get("ticketId");
        $this->commandBus->send(new AssignTicket($ticketId, $request->get("assignTo")));

        return new RedirectResponse("/tickets/" . $ticketId);
    }

    #[Route("/tickets/{ticketId}/release-assignation", methods:["POST"])]
    public function releaseAssignation(Request $request) : Response
    {
        $ticketId = $request->get("ticketId");
        $this->commandBus->send(new ReleaseTicket($ticketId));

        return new RedirectResponse("/tickets/" . $ticketId);
    }
}