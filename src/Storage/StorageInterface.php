<?php

namespace Void\OgImage\Storage;

use Void\OgImage\Exception\ImageStorageException;
use Void\OgImage\ImageResult;

interface StorageInterface
{
    /**
     * Save the image result to storage.
     *
     * @param ImageResult $result The image result to save
     * @param string      $path   The relative path/filename (e.g., 'post-123.png')
     *
     * @return string The stored path or public URL
     *
     * @throws ImageStorageException
     */
    public function save(ImageResult $result, string $path): string;
}
