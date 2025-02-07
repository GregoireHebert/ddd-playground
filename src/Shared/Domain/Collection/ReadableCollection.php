<?php

declare(strict_types=1);

namespace App\Shared\Domain\Collection;

use Doctrine\Common\Collections\ReadableCollection as ReadableCollectionInterface;

/**
 * @template TKey of array-key
 * @template T of object
 *
 * @template-implements ReadableCollectionInterface<TKey, T>
 *
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 * @SuppressWarnings("PHPMD.ShortVariable")
 * @SuppressWarnings("PHPMD.ElseExpression")
 */
final class ReadableCollection implements ReadableCollectionInterface
{
    /**
     * @param array<TKey, T> $elements
     */
    private function __construct(
        private array $elements = [],
    ) {
    }

    /**
     * Initializes a new ReadableCollection from another collection.
     *
     * @param ReadableCollectionInterface<TKey, T> $collection
     *
     * @return ReadableCollection<TKey, T>
     */
    public static function fromCollection(ReadableCollectionInterface $collection): self
    {
        return new self($collection->toArray());
    }

    /**
     * Initializes a new ReadableCollection from an array.
     *
     * @param array<TKey, T> $array
     *
     * @return self<TKey, T>
     */
    public static function fromArray(array $array): self
    {
        return new self($array);
    }

    /**
     * @param \Closure(T $a, T $b): int $p
     *
     * @return self<TKey, T>
     */
    public function sort(\Closure $p): self
    {
        $elements = $this->elements;
        usort($elements, $p);

        return new self($elements);
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->elements);
    }

    /**
     * @return \ArrayIterator<TKey, T>
     */
    #[\Override]
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @phpstan-param TKey $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->containsKey($offset);
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return $this->elements === [];
    }

    #[\Override]
    public function contains(mixed $element): bool
    {
        return \in_array($element, $this->elements, true);
    }

    #[\Override]
    public function containsKey(int|string $key): bool
    {
        return isset($this->elements[$key]) || \array_key_exists($key, $this->elements);
    }

    #[\Override]
    public function get(int|string $key)
    {
        return $this->elements[$key] ?? null;
    }

    #[\Override]
    public function getKeys(): array
    {
        return array_keys($this->elements);
    }

    #[\Override]
    public function getValues(): array
    {
        return array_values($this->elements);
    }

    #[\Override]
    public function toArray()
    {
        return $this->elements;
    }

    #[\Override]
    public function first()
    {
        return reset($this->elements);
    }

    #[\Override]
    public function last()
    {
        return end($this->elements);
    }

    #[\Override]
    public function key()
    {
        return key($this->elements);
    }

    #[\Override]
    public function current()
    {
        return current($this->elements);
    }

    #[\Override]
    public function next()
    {
        return next($this->elements);
    }

    #[\Override]
    public function slice(int $offset, ?int $length = null): array
    {
        return \array_slice($this->elements, $offset, $length, true);
    }

    /**
     * @param \Closure(TKey $key, T $value): bool $p
     */
    #[\Override]
    public function exists(\Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Closure(T $value, TKey $key): bool $p
     *
     * @return ReadableCollection<TKey, T>
     */
    #[\Override]
    public function filter(\Closure $p): self
    {
        return new self(array_filter($this->elements, $p, \ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @param \Closure(T): U $func
     *
     * @return ReadableCollection<TKey, U>
     *
     * @template U of object
     */
    #[\Override]
    public function map(\Closure $func): self
    {
        return new self(array_map($func, $this->elements));
    }

    /**
     * @param \Closure(TKey $key, T $value): bool $p
     *
     * @return array{self<TKey, T>, self<TKey, T>}
     */
    #[\Override]
    public function partition(\Closure $p): array
    {
        $matches = [];
        $noMatches = [];
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [new self($matches), new self($noMatches)];
    }

    /**
     * @param \Closure(TKey $key, T $value): bool $p
     */
    #[\Override]
    public function forAll(\Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if (!$p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @phpstan-param T $element
     *
     * @phpstan-return TKey|false
     */
    #[\Override]
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * @param \Closure(TKey $key, T $value): bool $p
     *
     * @phpstan-return T|null
     */
    #[\Override]
    public function findFirst(\Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return $element;
            }
        }

        return null;
    }

    /**
     * @phpstan-param \Closure(T $carry, T $item): ?T $func
     * @phpstan-param TInitial                        $initial
     *
     * @phpstan-return T|TInitial
     *
     * @template TInitial of mixed
     *
     * @param mixed|null $initial
     */
    #[\Override]
    public function reduce(\Closure $func, $initial = null)
    {
        return array_reduce($this->elements, $func, $initial);
    }
}
