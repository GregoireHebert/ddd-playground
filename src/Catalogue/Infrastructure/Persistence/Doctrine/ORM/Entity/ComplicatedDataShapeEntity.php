<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Doctrine\ORM\Entity;

class ComplicatedDataShapeEntity
{
    public function __construct(
        // pretend it's private and has getters
        public ?string $id = null,
        public ?string $complexeLabel = null,
        public ?int $dumbEnabled = null
    )
    {
    }
}
