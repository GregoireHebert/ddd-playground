<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Mapper;

use App\Catalogue\Domain\Models\SimplifiedEntity;
use App\Catalogue\Infrastructure\Persistence\Doctrine\ORM\Entity\ComplicatedDataShapeEntity;

class SimplifiedMapper {
    public static function toDomain(ComplicatedDataShapeEntity $entity): SimplifiedEntity {
        return SimplifiedEntity::fromDb(
            $entity->id,
            $entity->complexeLabel,
            (bool) $entity->dumbEnabled
        );
    }

    public static function toEntity(SimplifiedEntity $domain, ComplicatedDataShapeEntity $entity = null): ComplicatedDataShapeEntity {
        $entity = $entity ?? new ComplicatedDataShapeEntity($domain->id);
        $entity->complexeLabel = $domain->label;
        $entity->dumbEnabled = (int)$domain->enabled;

        return $entity;
    }
}
