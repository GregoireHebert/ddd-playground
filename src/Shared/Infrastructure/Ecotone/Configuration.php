<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Ecotone;

use Ecotone\Dbal\Configuration\DbalConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;
use Ecotone\SymfonyBundle\Config\SymfonyConnectionReference;

/**
 * Configure Ecotone.
 *
 * 1. Using Doctrine and repositories.
 * This allows a domain to map handlers directly to aggregate root, load it thanks to the identifier configuration,
 * for not complex operations that do not need domain services, nor application services.
 *
 * @link https://docs.ecotone.tech/modules/symfony/doctrine-orm
 *
 * @author Grégoire Hébert <contact@gheb.dev>
 */
class Configuration
{
    #[ServiceContext]
    public function getDbalConfiguration(): DbalConfiguration
    {
        return DbalConfiguration::createWithDefaults()
            ->withDoctrineORMRepositories(true);
    }

    # Configuration from Manager Registry Connection
    #[ServiceContext]
    public function getManagerRegistryConfiguration()
    {
        return SymfonyConnectionReference::defaultManagerRegistry('default');
    }
}
