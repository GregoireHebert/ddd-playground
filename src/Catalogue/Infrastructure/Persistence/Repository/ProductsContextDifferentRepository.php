<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Repository;

use App\Catalogue\Infrastructure\Persistence\ProductsContextDifferent\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method array<int, Phone> findAll()
 */
class ProductsContextDifferentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function pretendItsCreatedCorrectly() {
        $phone = new Phone();
        $phone->label = 'contextDifferentPhone';

        $this
            ->save($phone)
            ->flush();
    }

    public function save($entity): self
    {
        $this->getEntityManager()->persist($entity);
        return $this;
    }

    private function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
