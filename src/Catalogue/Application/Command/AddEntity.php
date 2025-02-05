<?php

declare(strict_types=1);

namespace App\Catalogue\Application\Command;

final readonly class AddEntity
{
    public function __construct(
        public string $id,
        public string $label,
    )
    {}
}
