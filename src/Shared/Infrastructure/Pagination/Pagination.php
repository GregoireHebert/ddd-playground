<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Pagination;

use App\Shared\Domain\Exception\PaginationException;
use App\Shared\Domain\Pagination\PaginationInterface;

/**
 * Help pagination elements manipulation in the application API providers.
 */
final readonly class Pagination implements PaginationInterface
{
    private int $offset;

    public static function fromContext(array $context): self
    {
        if (isset($context['pagination'])) {
            throw new \LogicException('Pagination is already initialized in the context and accessible at `$context[pagination]`.');
        }

        return new self(
            page: (int) ($context['filters']['page'] ?? 1),
            limit: (int) ($context['filters']['itemsPerPage'] ?? 30),
        );
    }

    private function __construct(
        private int $page,
        private int $limit,
    ) {
        if ($this->page <= 0) {
            throw PaginationException::invalidPage($this->page);
        }

        if ($this->limit <= 0) {
            throw PaginationException::invalidLimit($this->limit);
        }

        $this->offset = ($page - 1) * $limit;
    }

    #[\Override]
    public function page(): int
    {
        return $this->page;
    }

    #[\Override]
    public function limit(): int
    {
        return $this->limit;
    }

    #[\Override]
    public function offset(): int
    {
        return $this->offset;
    }
}
