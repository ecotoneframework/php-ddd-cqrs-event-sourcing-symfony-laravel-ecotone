<?php declare(strict_types=1);

namespace App\Application;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\User;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function register(string $name): void
    {
        try {
            $this->entityManager->beginTransaction();

            $user = User::register($name);
            $this->entityManager->persist($user);

            $this->entityManager->flush();
            $this->entityManager->commit();
        }catch (\Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }
}