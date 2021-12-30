<?php declare(strict_types=1);

namespace App\ReadModel;

use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;

class UsersQueryService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function getUsers(): array
    {
        return $this->entityManager->createQueryBuilder()
                ->select("u")
                ->from(User::class, "u")
                ->getQuery()
                ->getArrayResult();
    }
}