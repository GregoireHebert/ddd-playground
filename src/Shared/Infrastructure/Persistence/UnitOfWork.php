<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence;

use App\Shared\Infrastructure\Ecotone\TransactionWrapper;

/**
 * Given that the Domain Model cannot be directly mapped with doctrine because of its complexity
 *
 * When we actually have that typology of a domain model and want to store a new dataset or update an existing one,
 * we cannot use doctrine's unit of work to detect changes since this is not a doctrine entity.
 *
 * This class track the changes for the domain models.
 *
 * In a repository when one or more aggregate root are created
 * You as a developer must track it.
 *
 * Note: On the same path of automation, tracking should not be developer responsibility.
 *
 * <code>
 *     class SimplifiedDomainEntityRepository extends ServiceEntityRepository
 *     {
 *          public function __construct(private readonly UnitOfWork $uow, ManagerRegistry $registry)
 *          {
 *              parent::__construct($registry, ComplicatedDataShapeEntity::class);
 *          }
 *
 *          public function getNext(): string {
 *               return Uuid::uuid7()->toString();
 *          }
 *
 *          public function get(string $id): ?SimplifiedDomainEntity
 *          {
 *               $entity = $this->findOneBy(["id" => $id]); // complex doctrine entity
 *
 *              $simplified = SimplifiedEntityMapper::toDomain($entity);
 *              $this->uow->track($simplified);
 *
 *              return $simplified;
 *          }
 *      }
 * </code>
 *
 * For new ones, you should not need anything since it is covered through commands transaction.
 * @see TransactionWrapper::transactional()
 *
 * TODO: The changes compared with a shallow diff for now, a deep diff should be implemented.
 * TODO: Track deletion.
 * TODO: Wrap repository gets method to avoid having to do it.
 *
 * @author Grégoire Hébert <contact@gheb.dev>
 */
final class UnitOfWork
{
    private array $snapshots = [];
    private array $trackedObjects = [];
    private array $newObjects = [];

    /**
     * Track an existing aggregate root for changes
     */
    public function track(object $domainModel): void
    {
        $this->snapshots[spl_object_hash($domainModel)] = $this->snapshot($domainModel);
        $this->trackedObjects[spl_object_hash($domainModel)] = $domainModel;
    }

    /**
     * Track a new aggregate root
     */
    public function persist(object $domainModel): void
    {
        $this->newObjects[spl_object_hash($domainModel)] = true;
        $this->track($domainModel);
    }

    /**
     * @internal
     */
    public function hasChanged(object $domainModel): bool
    {
        $hash = spl_object_hash($domainModel);

        if (!isset($this->snapshots[$hash])) {
            return false;
        }

        return $this->snapshot($domainModel) !== $this->snapshots[$hash];
    }

    /**
     * @internal
     */
    public function isTracked(object $domainModel): bool
    {
        $hash = spl_object_hash($domainModel);

        return isset($this->snapshots[$hash]);
    }

    /**
     * @internal
     */
    public function isNew(object $domainModel): bool
    {
        $hash = spl_object_hash($domainModel);

        return isset($this->newObjects[$hash]);
    }

    /**
     * @internal
     * TODO: need a deep snapshot
     */
    private function snapshot(object $domainModel): array
    {
        return get_object_vars($domainModel);
    }

    public function getTrackedObjects(): array
    {
        return $this->trackedObjects;
    }
}
