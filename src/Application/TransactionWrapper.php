<?php declare(strict_types=1);

namespace App\Application;

use Doctrine\ORM\EntityManagerInterface;
use Ecotone\Messaging\Attribute\Interceptor\Around;
use Ecotone\Messaging\Handler\Processor\MethodInvoker\MethodInvocation;
use Ecotone\Modelling\Attribute\CommandHandler;

class TransactionWrapper
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Around(pointcut: CommandHandler::class)]
    public function transactional(MethodInvocation $methodInvocation)
    {
        try {
            $this->entityManager->beginTransaction();

            $methodInvocation->proceed();

            $this->entityManager->flush();
            $this->entityManager->commit();
        }catch (\Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }
}