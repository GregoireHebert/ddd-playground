<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Infrastructure\Persistence\ChangeTracker;
use App\Shared\Infrastructure\Persistence\RepositoryFactory;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreFlushEventArgs;

#[AsDoctrineListener('preFlush')]
class SyncDomainChangesSubscriber
{
    public function __construct(
        private readonly ChangeTracker $changeTracker,
        private readonly RepositoryFactory $repositoryFactory
    ) {}

    public function preFlush(PreFlushEventArgs $args): void {
        $trackedObjects = $this->changeTracker->getTrackedObjects();
        foreach ($trackedObjects as $object) {
            if ($this->changeTracker->hasChanged($object) || $this->changeTracker->isNew($object)) {
                $repository = $this->repositoryFactory->getRepositoryFor($object);
                $repository->save($object);
            }

            // TODO perform delete as well
        }
    }
}
