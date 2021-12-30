<?php declare(strict_types=1);

namespace App\UI\Controller;

use App\Application\UserService;
use App\Domain\Ticket\Ticket;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersApiController
{
    public function __construct(private UserService $userService) {}

    #[Route("/users", methods: ["POST"])]
    public function register(Request $request): Response
    {
        $this->userService->register($request->get("name"));

        return new RedirectResponse("/");
    }
}