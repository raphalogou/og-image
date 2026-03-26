<?php

namespace Void\OgImageBundle\Tests;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageManagerInterface;

class ImageManagerFactory
{
    public static function create(): ImageManagerInterface
    {
        // Check if Imagick extension is installed
        if (extension_loaded('imagick')) {
            return ImageManager::imagick();
        }

        // Fallback to GD (always available in PHP)
        return ImageManager::gd();
    }

    public static function getDriverName(): string
    {
        return extension_loaded('imagick') ? 'imagick' : 'gd';
    }
}
