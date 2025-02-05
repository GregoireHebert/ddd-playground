<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Repository;


use App\Catalogue\Domain\Models\SimplifiedEntity;
use App\Catalogue\Infrastructure\Persistence\Doctrine\ORM\Entity\ComplicatedDataShapeEntity;
use App\Catalogue\Infrastructure\Persistence\Mapper\SimplifiedMapper;
use App\Shared\Infrastructure\Persistence\ChangeTracker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

/**
 * @method array<int, SimplifiedEntity> findAll()
 * @method array<int, SimplifiedEntity> find()
 * @method SimplifiedEntity findOneBy(array $criteria)
 */
class SimplifiedEntityRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ChangeTracker $changeTracker, ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplicatedDataShapeEntity::class);
    }

    public function getNext(): string {
        return Uuid::uuid7()->toString();
    }

    public function get(string $id): ?SimplifiedEntity
    {
        $entity = $this->findOneBy(["id" => $id]);

        $simplified = SimplifiedMapper::toDomain($entity);
        $this->changeTracker->track($simplified);

        return $simplified;
    }

    public function save(SimplifiedEntity $simplifiedEntity): void {
        if ($this->changeTracker->hasChanged($simplifiedEntity) || $this->changeTracker->isNew($simplifiedEntity)) {
            $entity = $this->findOneBy(["id" => $simplifiedEntity->id]);
            $entity = SimplifiedMapper::toEntity($simplifiedEntity, $entity);
            $this->getEntityManager()->persist($entity);
        }
    }
}
