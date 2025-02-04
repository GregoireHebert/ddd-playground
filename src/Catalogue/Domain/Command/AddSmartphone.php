<?php

declare(strict_types=1);

namespace App\Catalogue\Domain\Command;

final readonly class AddSmartphone
{
    public function __construct(
        public ?string $id = null,
        public string $label,
    )
    {}
}
