<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\State\Pagination;

use ApiPlatform\State\Pagination\PaginatorInterface;
use App\Shared\Infrastructure\Pagination\PaginatedCollection;
use Traversable;
use IteratorAggregate;

/**
 * When a paginated resource is asked, this is what should be returned to API Platform to feed the Hydra correctly.
 * It takes a paginated collection which is the one you may pass to a classic controller.
 *
 * <code>
 *      public function provide(Operation $operation, array $uriVariables = [], array $context = []): PaginatedResourceCollection
 *      {
 *          // we get a PaginatedCollection.
 *          $smartphones = $this->queryBus->sendWithRouting(
 *              ClassicSmartphoneService::LIST_SMARTPHONES_FROM_CATALOGUE,
 *              new ListSmartphones(pagination: $context['pagination'])
 *          );
 *
 *          // map the domain model to resources
 *          $smartphones->map(fn(Smartphone $smartphone): SmartphoneResource => new SmartphoneResource($smartphone->id, $smartphone->label));
 *
 *          // return the paginatedResourceCollection for API Platform
 *          return new PaginatedResourceCollection($smartphones);
 *      }
 * </code>
 *
 * @author Grégoire Hébert <contact@gheb.dev>
 */
final readonly class PaginatedResourceCollection implements PaginatorInterface, IteratorAggregate
{
    public function __construct(private PaginatedCollection $paginatedCollection)
    {
    }

    #[\Override]
    public function count(): int
    {
        return $this->paginatedCollection->count();
    }

    #[\Override]
    public function getLastPage(): float
    {
        return $this->paginatedCollection->getLastPage();
    }

    #[\Override]
    public function getTotalItems(): float
    {
        return $this->paginatedCollection->getTotalItems();
    }

    #[\Override]
    public function getCurrentPage(): float
    {
        return $this->paginatedCollection->getCurrentPage();
    }

    #[\Override]
    public function getItemsPerPage(): float
    {
        return $this->paginatedCollection->getItemsPerPage();
    }

    public function getIterator(): Traversable
    {
        return $this->paginatedCollection;
    }
}
