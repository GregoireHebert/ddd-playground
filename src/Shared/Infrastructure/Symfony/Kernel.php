<?php

namespace App\Shared\Infrastructure\Symfony;

use ApiPlatform\Metadata\ApiResource;
use App\Shared\Infrastructure\Symfony\DependencyInjection\CompilerPass\ApiPlatform\AttributeResourcePass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[\Override]
    protected function build(ContainerBuilder $container): void
    {
        $this->autoconfigureApiPlatformResources($container);
    }

    /**
     * This method registers every class marked with the ApiResource attribute as an ApiPlatform Resource.
     * This way there is no need to declare mapping directories in API Platform configuration.
     *
     * @see AttributeResourcePass::process()
     *
     * @author Grégoire Hébert <contact@gheb.dev>
     * @author Jérôme Tamarelle <jerome@tamarelle.net>
     */
    private function autoconfigureApiPlatformResources(ContainerBuilder $container): void
    {
        // @see https://github.com/api-platform/core/pull/6943 for inspiration
        $container->registerAttributeForAutoconfiguration(ApiResource::class, static function (ChildDefinition $definition): void {
            $definition->addTag('api_platform.resource');
            // This line prevent using the resources as services since their intent is to be a representation DTO.
            $definition->addTag('container.excluded', ['source' => 'with #[ApiResource] attribute']);
        });

        $container->addCompilerPass(new AttributeResourcePass());
    }
}
