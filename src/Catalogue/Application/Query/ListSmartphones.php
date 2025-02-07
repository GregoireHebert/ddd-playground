<?php

declare(strict_types=1);

namespace App\Catalogue\Application\Query;

use App\Shared\Domain\Pagination\PaginationInterface;

final readonly class ListSmartphones
{
    public function __construct(
        public PaginationInterface $pagination,
    ) {
    }
}
