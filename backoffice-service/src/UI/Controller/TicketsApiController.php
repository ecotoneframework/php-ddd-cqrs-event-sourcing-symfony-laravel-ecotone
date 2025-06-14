<?php

namespace App\UI\Controller;

use App\Domain\Ticket\Command\ReleaseTicket;
use App\Domain\Ticket\Ticket;
use Ecotone\Modelling\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketsApiController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    #[Route("/tickets", methods: ["POST"])]
    public function prepare(Request $request): Response
    {
        $this->commandBus->sendWithRouting(
            Ticket::PREPARE_TICKET_TICKET,
            $request->request->all() + ["ticketId" => Uuid::uuid4()->toString()]
        );

        return new RedirectResponse("/");
    }

    #[Route("/tickets/{ticketId}/cancel", methods: ["POST"])]
    public function cancel(Request $request): Response
    {
        $ticketId = $request->get("ticketId");
        $this->commandBus->sendWithRouting(Ticket::CANCEL_TICKET, ["ticketId" => $ticketId]);

        return new RedirectResponse("/tickets/" . $ticketId);
    }

    #[Route("/tickets/{ticketId}/assign", methods: ["POST"])]
    public function assign(Request $request): Response
    {
        $ticketId = $request->get("ticketId");
        $this->commandBus->sendWithRouting(Ticket::ASSIGN_TICKET, array_merge(["ticketId" => $ticketId], $request->request->all()));

        return new RedirectResponse("/tickets/" . $ticketId);
    }
}