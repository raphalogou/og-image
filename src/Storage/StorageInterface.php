<?php

namespace Void\OgImageBundle\Storage;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Void\OgImageBundle\Exception\ImageStorageException;

interface StorageInterface
{
    /**
     * Save the encoded image to storage.
     *
     * @param EncodedImageInterface $image The encoded image to save
     * @param string                $path  The relative path/filename (e.g., 'post-123.webp')
     *
     * @return string The full path to the saved file
     *
     * @throws ImageStorageException
     */
    public function saveImage(EncodedImageInterface $image, string $path): string;
}
