<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

final class PaginationException extends \DomainException
{
    public static function invalidPage(int $page): self
    {
        return new self('The page must be at least 1, "%d" received.', [$page]);
    }

    public static function invalidLimit(int $limit): self
    {
        return new self('The limit must be at least 1, "%d" received.', [$limit]);
    }

    /** @param array<int, mixed> $parameters */
    private function __construct(string $message, array $parameters)
    {
        parent::__construct(\sprintf($message, ...$parameters));
    }
}
