<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Domain\Pagination\PaginationInterface;
use App\Shared\Domain\Pagination\PaginatorInterface;
use Doctrine\Common\Collections\ReadableCollection as ReadableCollectionInterface;

/**
 * @phpstan-template TKey of array-key
 * @phpstan-template T of object
 *
 * @phpstan-template-covariant T
 *
 * @template-covariant T
 */
interface RepositoryInterface
{
    /** @return ReadableCollectionInterface & PaginatorInterface<TKey, T> */
    public function paginate(PaginationInterface $pagination): ReadableCollectionInterface & PaginatorInterface;

    /**
     * @return T
     * @throws NotFoundException Thrown when the given object does not exist
     */
    public function get(object $id): object;

    public function add(object $period): void;

    /** @throws NotFoundException Thrown when the given object does not exist */
    public function remove(object $period): void;
}
