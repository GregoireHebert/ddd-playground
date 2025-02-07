<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Pagination;

use App\Shared\Domain\Collection\ReadableCollection;
use App\Shared\Domain\Pagination\PaginationInterface;
use App\Shared\Domain\Pagination\PaginationAdapterInterface;
use Doctrine\Common\Collections\ReadableCollection as ReadableCollectionInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Adapter use to query pagination data from a Doctrine ORM queryBuilder.
 *
 *  <code>
 *      QueryAdapter::from(queryBuilder: $queryBuilder)
 *  </code>
 */
final readonly class QueryAdapter implements PaginationAdapterInterface
{
    private Paginator $paginator;

    public static function fromQueryBuilder(QueryBuilder $queryBuilder): self
    {
        return new self(query: $queryBuilder->getQuery());
    }

    public function __construct(Query $query)
    {
        $this->paginator = new Paginator(query: $query, fetchJoinCollection: true);
        $this->paginator->setUseOutputWalkers(true);
    }

    #[\Override]
    public function getNumberOfItems(): float
    {
        return $this->paginator->count();
    }

    #[\Override]
    public function getResults(PaginationInterface $pagination): ReadableCollectionInterface
    {
        $data = $this->paginator->getQuery()
            ->setFirstResult($pagination->offset())
            ->setMaxResults($pagination->limit())
            ->getResult()
        ;

        return ReadableCollection::fromArray($data);
    }
}
