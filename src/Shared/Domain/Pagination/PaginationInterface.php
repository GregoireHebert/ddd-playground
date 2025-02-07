<?php

declare(strict_types=1);

namespace App\Shared\Domain\Pagination;

/**
 * Help pagination elements manipulation.
 */
interface PaginationInterface
{
    public function page(): int;

    public function limit(): int;

    public function offset(): int;
}
