<?php

declare(strict_types=1);

namespace App\Catalogue\Representation\Api\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Catalogue\Application\ClassicSmartphoneService;
use App\Catalogue\Application\Query\ListSmartphones;
use App\Catalogue\Domain\Models\Smartphone;
use App\Catalogue\Representation\Api\Resource\Smartphone as SmartphoneResource;
use App\Shared\Infrastructure\ApiPlatform\State\Pagination\PaginatedResourceCollection;
use App\Shared\Infrastructure\Pagination\PaginatedCollection;
use App\Shared\Infrastructure\Pagination\Pagination;
use Ecotone\Modelling\QueryBus;

class SmartphonesRead implements ProviderInterface
{
    public function __construct(
        private readonly QueryBus $queryBus,
    )
    {
    }

    /**
     * @param array{pagination: Pagination} $context
     */
    #[\Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): PaginatedResourceCollection
    {
        /** @var PaginatedCollection<$smartphones> */
        $smartphones = $this->queryBus->sendWithRouting(
            ClassicSmartphoneService::LIST_SMARTPHONES_FROM_CATALOGUE,
            new ListSmartphones(pagination: $context['pagination'])
        );

        $smartphones->map(fn (Smartphone $smartphone): SmartphoneResource => new SmartphoneResource($smartphone->id, $smartphone->label));

        return new PaginatedResourceCollection($smartphones);
    }
}
