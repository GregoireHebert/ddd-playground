<?php

declare(strict_types=1);

namespace App\Catalogue\Application\Query;

final readonly class ViewSmartphone
{
    public function __construct(
        public ?string $id = null,
    )
    {}
}
