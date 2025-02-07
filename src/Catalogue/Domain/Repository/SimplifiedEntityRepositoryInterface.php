<?php

declare(strict_types=1);

namespace App\Catalogue\Domain\Repository;

use App\Catalogue\Domain\Models\SimplifiedEntity;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Domain\Pagination\PaginatedCollectionInterface;
use App\Shared\Domain\Pagination\PaginationInterface;

interface SimplifiedEntityRepositoryInterface
{
    /**
     * @return string the next identifier for creating a smartphone
     */
    public function getNext(): string;

    /** @throws NotFoundException Thrown when the given period does not exist */
    public function get(string $id): SimplifiedEntity;

    public function add(SimplifiedEntity $simplifiedEntity): void;

    public function remove(SimplifiedEntity $simplifiedEntity): void;
}
