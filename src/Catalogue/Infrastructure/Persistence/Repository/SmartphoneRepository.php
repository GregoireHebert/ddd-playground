<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Repository;

use App\Catalogue\Domain\Models\Smartphone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method array<int, Smartphone> findAll()
 */
class SmartphoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Smartphone::class);
    }

    public function get(string $id): ?Smartphone
    {
        return $this->find($id);
    }

    public function save(Smartphone $smartphone): void {
        $this->getEntityManager()->persist($smartphone);
        $this->getEntityManager()->flush();
    }

    public function flush(): void {
        $this->getEntityManager()->flush();
    }
}
