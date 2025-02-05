<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence;

use App\Catalogue\Domain\Models\SimplifiedEntity;
use App\Catalogue\Infrastructure\Persistence\Doctrine\ORM\Entity\ComplicatedDataShapeEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class RepositoryFactory
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function getRepositoryFor(object $object): ObjectRepository
    {
        return match ($object::class) {
            SimplifiedEntity::class => $this->entityManager->getRepository(ComplicatedDataShapeEntity::class),
            default  => $this->entityManager->getRepository($object::class),
        };
    }
}
