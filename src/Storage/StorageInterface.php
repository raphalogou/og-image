<?php

namespace Void\OgImage\Storage;

use Void\OgImage\Exception\ImageStorageException;
use Void\OgImage\OpenGraphImage;

interface StorageInterface
{
    /**
     * Save the image result to storage.
     *
     * @param OpenGraphImage $result The image result to save
     * @param string         $path   The relative path/filename (e.g., 'post-123.png')
     *
     * @return string The stored path or public URL
     *
     * @throws ImageStorageException
     */
    public function save(OpenGraphImage $result, string $path): string;
}
