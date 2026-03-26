<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageManagerInterface;
use Void\OgImageBundle\Generator;
use Void\OgImageBundle\Storage\FilesystemStorage;
use Void\OgImageBundle\Storage\StorageInterface;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('void.og_image.storage.native', FilesystemStorage::class)
            ->args([
                abstract_arg('storage_dir'),
                service('logger'),
            ])
        ->alias(StorageInterface::class, 'void.og_image.storage.native')

        ->set('intervention.image_manager', ImageManager::class)
            ->factory(ImageManager::withDriver(...))
            ->arg(0, abstract_arg('driver'))
        ->alias(ImageManagerInterface::class, 'intervention.image_manager')

        ->set('void.og_image.generator', Generator::class)
            ->arg(0, service('intervention.image_manager'))
        ->alias(Generator::class, 'void.og_image.generator')
    ;
};
