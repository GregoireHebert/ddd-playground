<?php

declare(strict_types=1);

namespace App\Shared\Domain\Pagination;

use Doctrine\Common\Collections\ReadableCollection as ReadableCollectionInterface;

/**
 * An adapter provides pagination data to the {@see PaginatedCollection} according to its datasource.
 */
interface PaginationAdapterInterface
{
    public function getNumberOfItems(): float;

    public function getResults(PaginationInterface $pagination): ReadableCollectionInterface;
}
