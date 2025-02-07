<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Provider\ReadProvider;
use ApiPlatform\State\ProviderInterface;
use App\Shared\Infrastructure\Pagination\Pagination;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

/**
 * Decorates the main provider with a priority just after the {@see ReadProvider}
 * to have access to the `$context['filters']` parameter.
 *
 * It creates a Domain {@see Pagination} object to be sent through queries.
 *
 * <code>
 *     $smartphones = $this->queryBus->sendWithRouting(
 *          ClassicSmartphoneService::LIST_SMARTPHONES_FROM_CATALOGUE,
 *          new ListSmartphones(pagination: $context['pagination'])
 *      );
 * </code>
 *
 * In Symfony, higher priorities mean that decorators will be applied earlier.
 * A priority of 600 means it will be applied AFTER the {@see ReadProvider} priority (500).
 *
 * @see https://symfony.com/doc/current/service_container/service_decoration.html#decoration-priority
 */
#[AsDecorator(decorates: 'api_platform.state_provider.main', priority: 600)]
final readonly class PaginationContextProvider implements ProviderInterface
{
    public function __construct(
        #[AutowireDecorated]
        private ProviderInterface $provider,
    ) {
    }

    #[\Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $context['pagination'] = null;

        if ($this->supports($operation)) {
            $context['pagination'] = Pagination::fromContext($context);
        }

        return $this->provider->provide($operation, $uriVariables, $context);
    }

    private function supports(Operation $operation): bool
    {
        if (!$operation instanceof CollectionOperationInterface) {
            return false;
        }

        if ($operation->getPaginationEnabled() === false) {
            return false;
        }

        return $operation->getPaginationClientEnabled() !== false;
    }
}
