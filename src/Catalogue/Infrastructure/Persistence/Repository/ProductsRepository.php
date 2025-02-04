<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Repository;

use App\Catalogue\Infrastructure\Persistence\Products\Phone;
use App\Catalogue\Infrastructure\Persistence\Products\Product;
use App\Catalogue\Infrastructure\Persistence\Products\SpecificPhone;
use App\Catalogue\Infrastructure\Persistence\Products\Wearable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method array<int, Phone|Wearable> findAll()
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function pretendItsCreatedCorrectly() {
        $phone = new Phone();
        $phone->label = 'YPhoneLabel';
        $phone->phoneSpecificInfo = 'YPhone';

        $specificPhone = new SpecificPhone();
        $specificPhone->label = 'YPhoneLabel';
        $specificPhone->phoneSpecificInfo = 'YPhone';
        $specificPhone->verySpecificInfo = 'superSpecific';

        $wearable = new Wearable();
        $wearable->label = 'YPhoneLabel';
        $wearable->wearableSpecificInfo = 'YPhone';

        $this
            ->save($phone)
            ->save($wearable)
            ->save($specificPhone)
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
