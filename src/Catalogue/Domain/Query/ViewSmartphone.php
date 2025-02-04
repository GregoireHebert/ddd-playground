<?php

declare(strict_types=1);

namespace App\Catalogue\Domain\Query;

final readonly class ViewSmartphone
{
    public function __construct(
        public ?string $id = null,
    )
    {}
}
