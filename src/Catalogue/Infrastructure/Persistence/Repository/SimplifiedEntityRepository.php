<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Repository;

use App\Catalogue\Domain\Models\SimplifiedEntity;
use App\Catalogue\Domain\Repository\SimplifiedEntityRepositoryInterface;
use App\Catalogue\Infrastructure\Persistence\Doctrine\ORM\Entity\ComplicatedDataShapeEntity;
use App\Catalogue\Infrastructure\Persistence\Mapper\SimplifiedMapper;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Infrastructure\Persistence\UnitOfWork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

/**
 * @method array<int, SimplifiedEntity> findAll()
 * @method array<int, SimplifiedEntity> find()
 * @method ?SimplifiedEntity findOneBy(array $criteria)
 */
class SimplifiedEntityRepository extends ServiceEntityRepository implements SimplifiedEntityRepositoryInterface
{
    public function __construct(private readonly UnitOfWork $uow, ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplicatedDataShapeEntity::class);
    }

    public function getNext(): string
    {
        return Uuid::uuid7()->toString();
    }

    #[\Override]
    public function get(string $id): SimplifiedEntity
    {
        if (null === $entity = $this->findOneBy(["id" => $id])) {
            throw new NotFoundException();
        }

        $simplified = SimplifiedMapper::toDomain($entity);
        $this->uow->track($simplified);

        return $simplified;
    }

    #[\Override]
    public function add(SimplifiedEntity $simplifiedEntity): void
    {
        if ($this->uow->hasChanged($simplifiedEntity) || $this->uow->isNew($simplifiedEntity)) {
            $entity = $this->findOneBy(["id" => $simplifiedEntity->id]);
            $entity = SimplifiedMapper::toEntity($simplifiedEntity, $entity);
            $this->getEntityManager()->persist($entity);
        }
    }

    #[\Override]
    public function remove(SimplifiedEntity $simplifiedEntity): void {
        if (null !== $entity = $this->findOneBy(["id" => $simplifiedEntity->id])) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }
    }
}
