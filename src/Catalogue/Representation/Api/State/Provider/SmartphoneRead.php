<?php

declare(strict_types=1);

namespace App\Catalogue\Representation\Api\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PartialPaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use App\Catalogue\Application\ClassicSmartphoneService;
use App\Catalogue\Representation\Api\Resource\Smartphone;
use Ecotone\Modelling\QueryBus;
use Ramsey\Uuid\Rfc4122\UuidV7;

class SmartphoneRead implements ProviderInterface
{
    public function __construct(
        private readonly QueryBus $queryBus,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var \App\Catalogue\Domain\Models\Smartphone $domainSmartPhone */
        $domainSmartPhone = $this->queryBus->sendWithRouting(
            ClassicSmartphoneService::VIEW_SMARTPHONE_FROM_CATALOGUE,
            $uriVariables
        );

        return new Smartphone($domainSmartPhone->id, $domainSmartPhone->label);
    }
}
