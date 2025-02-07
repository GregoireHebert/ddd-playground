<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Pagination;

use App\Shared\Domain\Pagination\PaginatedCollectionInterface;
use App\Shared\Domain\Pagination\PaginationAdapterInterface;
use App\Shared\Domain\Pagination\PaginatorInterface;
use Closure;
use Doctrine\Common\Collections\ReadableCollection as ReadableCollectionInterface;
use Traversable;

/**
 * The PaginatedCollection is used to preserve pagination data through the Application, Domain and Infrastructure layers.
 * It can be used with multiple adapters like the {@see QueryAdapter} or the {@see ReadableCollectionAdapter}.
 *
 * Usage with {@see QueryAdapter}:
 * <code>
 *     // In the API Platform provider
 *     $this->queryBus->dispatch(new GetCalendarPeriodCollection(pagination: $context['pagination'] ?? null));
 *
 *     // in the Repository
 *     $queryBuilder = $this->createQueryBuilder('periods');
 *
 *     PaginatedCollection::fromAdapter(
 *         adapter: QueryAdapter::from(
 *             queryBuilder: $queryBuilder,
 *             pagination: $context['pagination'],
 *         )
 *     );
 * </code>
 *
 * The collection can be mapped (to transform Domain objects to resources for instance):
 * <code>
 *     // This will return a PaginatedCollection to preserve pagination data
 *     $collection->map(fn (CalendarPeriod $period): CalendarPeriodResource => $this->mapper->toResource($period)
 * </code>
 *
 * @phpstan-template TKey of array-key
 * @phpstan-template T of object
 *
 * @phpstan-template-covariant T
 *
 * @template-covariant T
 *
 * @SuppressWarnings("PHPMD.ShortVariable")
 */
final readonly class PaginatedCollection implements PaginatedCollectionInterface
{
    public static function fromAdapter(
        PaginationAdapterInterface $adapter,
        Pagination $pagination,
    ): self {
        return new self(
            data: $adapter->getResults($pagination),
            numberOfItems: $adapter->getNumberOfItems(),
            pagination: $pagination,
        );
    }

    /**
     * @param ReadableCollectionInterface<TKey, T> $data
     */
    private function __construct(
        private ReadableCollectionInterface $data,
        private float $numberOfItems,
        private Pagination $pagination,
    ) {
    }

    /**
     * @phpstan-template U of object
     *
     * @phpstan-param Closure(T):U $func
     *
     * @phpstan-return static<TKey, U>
     *
     * @return static<TKey, U>
     */
    #[\Override]
    public function map(\Closure $func): self
    {
        return new self(
            data: $this->data->map($func),
            numberOfItems: $this->numberOfItems,
            pagination: $this->pagination,
        );
    }

    /**
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    #[\Override]
    public function filter(\Closure $p): self
    {
        // filtering would cause the PaginatedCollection Value Object to become invalid
        // as the numberOfItems would no longer match the number of items in the contained Collection
        throw new \LogicException('You cannot filter a PaginatedCollection.');
    }

    /**
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    #[\Override]
    public function reduce(\Closure $func, mixed $initial = null): self
    {
        // reducing would cause the PaginatedCollection Value Object to become invalid
        // as the numberOfItems would no longer match the number of items in the contained Collection
        throw new \LogicException('You cannot reduce a PaginatedCollection.');
    }

    #[\Override]
    public function partition(\Closure $p): array
    {
        return $this->data->partition($p);
    }

    /**
     * @return \Traversable<int|string, mixed>
     *
     * @phpstan-return Traversable<TKey, T>
     */
    #[\Override]
    public function getIterator(): \Traversable
    {
        return $this->data->getIterator();
    }

    #[\Override]
    public function count(): int
    {
        return $this->data->count();
    }

    /**
     * @param mixed $element the element to search for
     *
     * @phpstan-param TMaybeContained $element
     *
     * @return bool TRUE if the collection contains the element, FALSE otherwise
     *
     * @phpstan-return (TMaybeContained is T ? bool : false)
     *
     * @template TMaybeContained
     */
    #[\Override]
    public function contains(mixed $element): bool
    {
        return $this->data->contains($element);
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return $this->data->isEmpty();
    }

    /**
     * @param string|int $key the key/index to check for
     *
     * @phpstan-param TKey $key
     */
    #[\Override]
    public function containsKey(int|string $key): bool
    {
        return $this->data->containsKey($key);
    }

    /**
     * @param string|int $key the key/index of the element to retrieve
     *
     * @phpstan-param TKey $key
     *
     * @phpstan-return T|null
     */
    #[\Override]
    public function get(int|string $key): mixed
    {
        return $this->data->get($key);
    }

    #[\Override]
    public function getKeys(): array
    {
        return $this->data->getKeys();
    }

    #[\Override]
    public function getValues(): array
    {
        return $this->data->getValues();
    }

    #[\Override]
    public function toArray(): array
    {
        return $this->data->toArray();
    }

    #[\Override]
    public function first(): mixed
    {
        return $this->data->first();
    }

    #[\Override]
    public function last(): mixed
    {
        return $this->data->last();
    }

    #[\Override]
    public function key(): int|string|null
    {
        return $this->data->key();
    }

    #[\Override]
    public function current(): mixed
    {
        return $this->data->current();
    }

    #[\Override]
    public function next(): mixed
    {
        return $this->data->next();
    }

    #[\Override]
    public function slice(int $offset, ?int $length = null): array
    {
        return $this->data->slice($offset, $length);
    }

    #[\Override]
    public function exists(\Closure $p): bool
    {
        return $this->data->exists($p);
    }

    #[\Override]
    public function forAll(\Closure $p): bool
    {
        return $this->data->forAll($p);
    }

    /**
     * @param mixed $element the element to search for
     *
     * @phpstan-param TMaybeContained $element
     *
     * @return int|string|bool the key/index of the element or FALSE if the element was not found
     *
     * @phpstan-return (TMaybeContained is T ? TKey|false : false)
     *
     * @template TMaybeContained
     */
    #[\Override]
    public function indexOf(mixed $element): int|string|bool
    {
        return $this->data->indexOf($element);
    }

    #[\Override]
    public function findFirst(\Closure $p): mixed
    {
        return $this->data->findFirst($p);
    }

    #[\Override]
    public function getLastPage(): float
    {
        if ($this->pagination->limit() <= 0) {
            return 1;
        }

        return ceil($this->numberOfItems / $this->pagination->limit()) ?: 1;
    }

    #[\Override]
    public function getTotalItems(): float
    {
        return $this->numberOfItems;
    }

    #[\Override]
    public function getCurrentPage(): float
    {
        return (float) $this->pagination->page();
    }

    #[\Override]
    public function getItemsPerPage(): float
    {
        return (float) $this->pagination->limit();
    }
}
