<?php

declare(strict_types=1);

namespace App\Catalogue\Domain\Repository;

use App\Catalogue\Domain\Models\Smartphone;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Domain\Pagination\PaginatedCollectionInterface;
use App\Shared\Domain\Pagination\PaginationInterface;

interface SmartphoneRepositoryInterface
{
    /**
     * @return string the next identifier for creating a smartphone
     */
    public function getNext(): string;

    /** @return PaginatedCollectionInterface<int|string, Smartphone> */
    public function paginate(PaginationInterface $pagination): PaginatedCollectionInterface;

    /** @throws NotFoundException Thrown when the given period does not exist */
    public function get(string $id): Smartphone;

    public function add(Smartphone $smartphone): void;

    public function remove(Smartphone $smartphone): void;
}
