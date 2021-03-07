<?php

namespace App\UI\Controller;

use App\ReadModel\LastPreparedTicketsProjection;
use App\ReadModel\UnassignedTicketsProjection;
use Ecotone\Modelling\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketViewController extends AbstractController
{
    private QueryBus $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    #[Route("/")]
    public function lastPreparedTickets() : Response
    {
        return $this->render("last_prepared_tickets.html.twig",[
            "tickets" => $this->queryBus->sendWithRouting(LastPreparedTicketsProjection::GET_PREPARED_TICKETS)
        ]);
    }

    #[Route("/tickets/{ticketId}")]
    public function ticketDetails(Request $request) : Response
    {
        return $this->render("ticket_details.html.twig", $this->queryBus->sendWithRouting(LastPreparedTicketsProjection::GET_TICKET_DETAILS, $request->get("ticketId")));
    }

    #[Route("/prepare-ticket")]
    public function prepareTicket() : Response
    {
        return $this->render("prepare_ticket.html.twig");
    }

    #[Route("/unassigned-tickets")]
    public function unassignedTickets() : Response
    {
        return $this->render("unassigned_tickets.html.twig", [
            "tickets" => $this->queryBus->sendWithRouting(UnassignedTicketsProjection::GET_UNASSIGED_TICKETS)
        ]);
    }
}