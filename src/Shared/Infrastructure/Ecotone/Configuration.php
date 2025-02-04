<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Ecotone;

use Ecotone\Dbal\Configuration\DbalConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;
use Ecotone\SymfonyBundle\Config\SymfonyConnectionReference;

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
