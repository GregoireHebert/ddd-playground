<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Repository;

use App\Catalogue\Domain\Models\Smartphone;
use App\Catalogue\Domain\Repository\SmartphoneRepositoryInterface;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Domain\Pagination\PaginationInterface;
use App\Shared\Infrastructure\Pagination\PaginatedCollection;
use App\Shared\Infrastructure\Persistence\Pagination\QueryAdapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

/**
 * @method array<int, Smartphone> findAll()
 */
class SmartphoneRepository extends ServiceEntityRepository implements SmartphoneRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Smartphone::class);
    }

    #[\Override]
    public function getNext(): string {
        return Uuid::uuid7()->toString();
    }

    #[\Override]
    public function get(string $id): Smartphone
    {
        return $this->find($id) ?? throw new NotFoundException();
    }

    #[\Override]
    public function add(Smartphone $smartphone): void {
        $this->getEntityManager()->persist($smartphone);
        $this->getEntityManager()->flush();
    }

    #[\Override]
    public function remove(Smartphone $smartphone): void {
        $this->getEntityManager()->remove($smartphone);
        $this->getEntityManager()->flush();
    }

    public function flush(): void {
        $this->getEntityManager()->flush();
    }

    /**
     * @return PaginatedCollection<int|string, Smartphone>
     */
    public function paginate(PaginationInterface $pagination): PaginatedCollection
    {
        $queryBuilder = $this->createQueryBuilder('smartphones')
            ->setMaxResults($pagination->limit())
            ->setFirstResult($pagination->offset())
        ;

        return PaginatedCollection::fromAdapter(QueryAdapter::fromQueryBuilder($queryBuilder), $pagination);
    }
}
