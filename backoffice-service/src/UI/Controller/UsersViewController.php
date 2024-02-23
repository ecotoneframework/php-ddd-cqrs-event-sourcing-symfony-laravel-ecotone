<?php declare(strict_types=1);

namespace App\UI\Controller;

use App\ReadModel\UsersQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersViewController extends AbstractController
{
    public function __construct(private UsersQueryService $usersQueryService) {}

    #[Route("/")]
    public function lastPreparedTickets() : Response
    {
        return $this->render("user_list.html.twig", ["users" => $this->usersQueryService->getUsers()]);
    }

    #[Route("/register-user")]
    public function prepareTicket() : Response
    {
        return $this->render("register_user.html.twig");
    }
}