<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Infrastructure\Persistence\UnitOfWork;
use App\Shared\Infrastructure\Persistence\RepositoryFactory;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreFlushEventArgs;

/**
 * Given that the Domain Model cannot be directly mapped with doctrine because of its complexity
 *
 * When we actually have that typology of a domain model and want to store a new dataset or update an existing one,
 * we cannot use doctrine's unit of work to detect changes since this is not a doctrine entity.
 *
 * Although we could manually retrieve the repository and call the save method,
 * This allows to consider every change made to an existing Entity (in the Domain sense)
 * and every command created ones to be stored.
 *
 * Despite being disconcerting at first, this behaviour is closer to repository definitions
 * and expectations, plus, it reduces the code necessary to produce domain side that are just technical pollution.
 *
 * When a flush is called, during pre flush, this class will traverse all tracked domain objects
 * and if they have changed since tracking or if they are new, will call the save repositoryMethod.
 * In response the save method will have the responsibility to transform the domain model into a doctrine entity
 * and call the persist method if need be.
 *
 * Note: One could argue for explicitness or control, I understand and wouldn't fight it.
 * This all auto-save mechanism is here for comfort and getting closer to an ideal.
 *
 * @author Grégoire Hébert <contact@gheb.dev>
 */
#[AsDoctrineListener('preFlush')]
class SyncDomainChangesSubscriber
{
    public function __construct(
        private readonly UnitOfWork        $uow,
        private readonly RepositoryFactory $repositoryFactory
    ) {}

    public function preFlush(PreFlushEventArgs $args): void {
        $trackedObjects = $this->uow->getTrackedObjects();
        foreach ($trackedObjects as $object) {
            if ($this->uow->hasChanged($object) || $this->uow->isNew($object)) {
                // TODO add repository interface
                $repository = $this->repositoryFactory->getRepositoryFor($object);
                $repository->add($object);
            }

            // TODO perform delete as well
        }
    }
}
