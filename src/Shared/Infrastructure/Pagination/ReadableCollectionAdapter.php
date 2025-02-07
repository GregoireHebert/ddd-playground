<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Pagination;

use App\Shared\Domain\Collection\ReadableCollection;
use App\Shared\Domain\Pagination\PaginationAdapterInterface;
use App\Shared\Domain\Pagination\PaginationInterface;
use Doctrine\Common\Collections\ReadableCollection as ReadableCollectionInterface;

/**
 * Adapter to read and preserve pagination data from a ReadableCollection.
 *
 *  <code>
 *      ReadableCollectionAdapter::fromCollection(collection: $collection, numberOfItems: $count)
 *      ReadableCollectionAdapter::fromArray(collection: $data, numberOfItems: $count)
 *  </code>
 */
final readonly class ReadableCollectionAdapter implements PaginationAdapterInterface
{
    public static function fromCollection(ReadableCollectionInterface $data, ?float $numberOfItems = null): self
    {
        return new self(data: $data, numberOfItems: $numberOfItems ?? $data->count());
    }

    public static function fromArray(array $data, ?float $numberOfItems = null): self
    {
        return new self(data: ReadableCollection::fromArray($data), numberOfItems: $numberOfItems ?? \count($data));
    }

    private function __construct(
        private ReadableCollectionInterface $data,
        private float $numberOfItems,
    ) {
    }

    #[\Override]
    public function getNumberOfItems(): float
    {
        return $this->numberOfItems;
    }

    #[\Override]
    public function getResults(PaginationInterface $pagination): ReadableCollectionInterface
    {
        return ReadableCollection::fromArray($this->data->slice(offset: $pagination->offset(), length: $pagination->limit()));
    }
}
