<?php

declare(strict_types=1);

namespace App\Catalogue\Application\Command;

final readonly class AddSmartphone
{
    public function __construct(
        public string $id,
        public string $label,
    )
    {}
}
