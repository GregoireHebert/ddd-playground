<?php

declare(strict_types=1);

namespace App\Shared\Domain\Pagination;

/**
 * Partial Paginator Interface.
 *
 * @template T of object
 *
 * @extends \Traversable<T>
 */
interface PartialPaginatorInterface extends \Traversable, \Countable
{
    /**
     * Gets the current page number.
     */
    public function getCurrentPage(): float;

    /**
     * Gets the number of items by page.
     */
    public function getItemsPerPage(): float;
}
