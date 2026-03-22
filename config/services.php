<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

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

        ->set('void.og_image.generator', Generator::class)
        ->alias(Generator::class, 'void.og_image.generator')
    ;
};
