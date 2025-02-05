<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Ecotone;

use App\Shared\Infrastructure\Persistence\ChangeTracker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\Proxy;
use Ecotone\Messaging\Attribute\Interceptor\Around;
use Ecotone\Messaging\Handler\Processor\MethodInvoker\MethodInvocation;
use Ecotone\Modelling\Attribute\CommandHandler;

class TransactionWrapper
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ChangeTracker $changeTracker
    ) {}

    #[Around(pointcut: CommandHandler::class)]
    public function transactional(MethodInvocation $methodInvocation)
    {
        try {
            $this->entityManager->beginTransaction();

            if (null !== $data = $methodInvocation->proceed()) {
                if(!$this->isDoctrineEntity($data) && !$this->changeTracker->isTracked($data)) {
                    $this->changeTracker->trackAsNew($data);
                } elseif ($this->isDoctrineEntity($data) && !$this->stillUnknown($data)) {
                    $this->entityManager->persist($data);
                }
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    private function isDoctrineEntity(object $class): bool
    {
        if (is_object($class)) {
            $class = ($class instanceof Proxy)
                ? get_parent_class($class)
                : get_class($class);
        }

        return !$this->entityManager->getMetadataFactory()->isTransient($class);
    }

    private function stillUnknown(object $object): bool
    {
        return !$this->entityManager->contains($object);
    }
}
