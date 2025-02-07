<?php

declare(strict_types=1);

namespace App\Shared\Domain\Pagination;

use App\Shared\Infrastructure\Pagination\PaginatedCollection;
use Doctrine\Common\Collections\ReadableCollection as ReadableCollectionInterface;

/**
 * @see PaginatedCollection
 */
interface PaginatedCollectionInterface extends ReadableCollectionInterface, PaginatorInterface
{
}
