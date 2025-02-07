<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence;

use App\Catalogue\Domain\Models\SimplifiedEntity;
use App\Catalogue\Infrastructure\Persistence\Doctrine\ORM\Entity\ComplicatedDataShapeEntity;
use App\Shared\Infrastructure\Persistence\Doctrine\SyncDomainChangesSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Given that the Domain Model cannot be directly mapped with doctrine because of its complexity
 *
 * We create an DOMAIN/Infrastructure/Persistence/Doctrine/ORM/Entity/YOURCOMPLEXENTITIES class(es)
 * with all the intricacies in involves, it could be a complex structure based on multiple tables.
 *
 * The Doctrine mapping appears in the DOMAIN/Infrastructure/Persistence/Doctrine/ORM/ManualMapping directory
 * Although, because the domain wants to manipulate domain models through the repository,
 * we associate this doctrine mapping with the domain model repository.
 *
 * Then, when we actually have that typology of domain model and want to retrieve the associated repository
 * to store a new dataset or update an existing one, we cannot use the entity manager
 * since this is not a doctrine entity.
 *
 * This factory return the associated repository.
 *
 * As of now, the association is done here.
 * For comfort, it may later be done through an attribute.
 *
 * This class is used when
 * @see SyncDomainChangesSubscriber::preFlush
 *
 * @author Grégoire Hébert <contact@gheb.dev>
 */
final readonly class RepositoryFactory
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function getRepositoryFor(object $object): ObjectRepository
    {
        return match ($object::class) {
            SimplifiedEntity::class => $this->entityManager->getRepository(ComplicatedDataShapeEntity::class),
            default  => $this->entityManager->getRepository($object::class),
        };
    }
}
