<?php

namespace Void\OgImage\Symfony;

use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Void\OgImage\Generator;
use Void\OgImage\Storage\LocalStorage;
use Void\OgImage\Storage\StorageInterface;

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
                ->enumNode('driver')
                    ->values(['gd', 'imagick'])
                    ->defaultValue('imagick')
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->register('void.og_image.storage.native', LocalStorage::class)
            ->setArguments([
                $config['storage_dir'],
                new Reference('logger'),
            ])
        ;
        $builder->setAlias(StorageInterface::class, 'void.og_image.storage.native');

        $builder->register('intervention.image_manager', ImageManager::class)
            ->setFactory([ImageManager::class, 'withDriver'])
            ->setArgument(0, 'gd' === $config['driver'] ? GdDriver::class : ImagickDriver::class)
        ;

        $builder->register('void.og_image.generator', Generator::class)
            ->setArgument(0, new Reference('intervention.image_manager'))
        ;
        $builder->setAlias(Generator::class, 'void.og_image.generator');
    }
}
