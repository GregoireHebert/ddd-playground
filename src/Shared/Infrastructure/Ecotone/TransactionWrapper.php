<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Ecotone;

use App\Shared\Infrastructure\Persistence\UnitOfWork as UoW;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Proxy;
use Ecotone\Messaging\Attribute\Interceptor\Around;
use Ecotone\Messaging\Handler\Processor\MethodInvoker\MethodInvocation;
use Ecotone\Modelling\Attribute\CommandHandler;

/**
 * Wraps every ecotone CQS command to set a transaction for it.
 * This encloses the commands to avoid some edge cases where multiple ones should be done.
 *
 * It is more an integrity failsafe than a real need since in theory one should not manipulate
 * more than one aggregate at once.
 *
 * In addition, every command will be expected to return the data it created.
 * although it must not be used from the UI layer.
 *
 * Thanks to this, for every new aggregate root, we can save them without having to add code domain side.
 * if it exists, it is saved.
 *
 * @author Grégoire Hébert <contact@gheb.dev>
 */
class TransactionWrapper
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UoW $uow
    ) {}

    #[Around(pointcut: CommandHandler::class)]
    public function transactional(MethodInvocation $methodInvocation)
    {
        try {
            $this->entityManager->beginTransaction();

            // TODO control they are aggregate roots only
            if (null !== $data = $methodInvocation->proceed()) {
                if(!$this->isDoctrineEntity($data) && !$this->uow->isTracked($data)) {
                    $this->uow->persist($data);
                } elseif ($this->isDoctrineEntity($data) && $this->stillUnknown($data)) {
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
