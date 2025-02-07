<?php

declare(strict_types=1);

namespace App\Shared\Domain\Pagination;

/**
 * The \Countable implementation should return the number of items on the
 * current page, as an integer.
 *
 * @template T of object
 *
 * @extends PartialPaginatorInterface<T>
 */
interface PaginatorInterface extends PartialPaginatorInterface
{
    /**
     * Gets last page.
     */
    public function getLastPage(): float;

    /**
     * Gets the number of items in the whole collection.
     */
    public function getTotalItems(): float;
}
