<?php

namespace Void\OgImageBundle\Storage;

use Intervention\Image\Interfaces\EncodedImageInterface;

interface StorageInterface
{
    /**
     * Save the encoded image to storage.
     *
     * @param EncodedImageInterface $image     The encoded image to save
     * @param string                $path      The relative path/filename (e.g., 'post-123.webp')
     * @param string|null           $directory Optional subdirectory (e.g., 'posts')
     *
     * @return string|null The full path to the saved file, or null on failure
     */
    public function saveImage(EncodedImageInterface $image, string $path, ?string $directory = null): ?string;
}
