<?php

namespace Void\OgImageBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class OgImageBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->stringNode('storage_dir')
                    ->defaultValue('%kernel.project_dir%/public/og-images')
                    ->validate()
                        ->ifFalse(fn (string $directory) => file_exists($directory))
                        ->thenInvalid('The storage directory %s does not exist')
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');

        $builder->getDefinition('void.og_image.storage.native')
            ->setArgument(0, $config['storage_dir'])
        ;
    }
}
