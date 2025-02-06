<?php

declare(strict_types=1);

namespace App\Shared\Symfony\DependencyInjection\CompilerPass\ApiPlatform;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AttributeResourcePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $classes = (array)$container->getParameter('api_platform.class_name_resources');

        // findTaggedServiceIds cannot be used, as the services are excluded
        foreach ($container->getDefinitions() as $definition) {
            if ($definition->hasTag('api_platform.resource')) {
                $classes[] = $definition->getClass();
            }
        }

        $container->setParameter('api_platform.class_name_resources', array_unique($classes));
    }
}
