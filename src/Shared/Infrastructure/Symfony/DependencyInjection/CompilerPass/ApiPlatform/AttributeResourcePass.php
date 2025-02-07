<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\DependencyInjection\CompilerPass\ApiPlatform;

use App\Shared\Infrastructure\Symfony\Kernel;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This pass add every class marked with the `api_platform.resource` tagged by Kernel::autoconfigureApiPlatformResources method.
 * This way there is no need to declare mapping directories in API Platform configuration.
 *
 * @internal
 * @see Kernel::autoconfigureApiPlatformResources()
 *
 * @author Grégoire Hébert <contact@gheb.dev>
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
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
