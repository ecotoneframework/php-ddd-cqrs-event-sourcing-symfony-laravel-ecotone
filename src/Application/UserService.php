<?php declare(strict_types=1);

namespace App\Application;

use App\Domain\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\User;

class UserService
{
    public function __construct(private EntityManagerInterface $entityManager, private EmailSender $emailSender) {}

    public function registerUser(string $name): void
    {
        try {
            $this->entityManager->beginTransaction();

            $user = User::register($name);
            $this->entityManager->persist($user);

            $this->emailSender->sendWelcomeEmailTo($user->getUserId());

            $this->entityManager->flush();
            $this->entityManager->commit();
        }catch (\Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    public function activateUser(string $id): void
    {
        try {
            $this->entityManager->beginTransaction();

            /** @var User $user */
            $user = $this->entityManager->find(User::class, $id);
            $user->activate();
            $this->entityManager->persist($user);

            $this->entityManager->flush();
            $this->entityManager->commit();
        }catch (\Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }
}