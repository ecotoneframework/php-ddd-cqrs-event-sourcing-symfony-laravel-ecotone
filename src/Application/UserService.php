<?php declare(strict_types=1);

namespace App\Application;

use App\Domain\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\User;
use Ecotone\Modelling\Attribute\CommandHandler;

class UserService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[CommandHandler("registerUser")]
    public function registerUser(string $name): void
    {
        $user = User::register($name);
        $this->entityManager->persist($user);
    }

    #[CommandHandler("activateUser")]
    public function activateUser(string $id): void
    {
        /** @var User $user */
        $user = $this->entityManager->find(User::class, $id);
        $user->activate();
        $this->entityManager->persist($user);
    }
}