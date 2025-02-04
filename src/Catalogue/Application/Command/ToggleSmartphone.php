<?php

declare(strict_types=1);

namespace App\Catalogue\Application\Command;

final readonly class ToggleSmartphone
{
    public function __construct(
        public ?string $id = null,
    )
    {}
}
