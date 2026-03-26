<?php

namespace Void\OgImage\Storage;

use Void\OgImage\Exception\ImageStorageException;
use Void\OgImage\ImageResult;

class FilesystemStorage implements StorageInterface
{
    public function __construct(private readonly string $storageDirectory)
    {
        if (!file_exists($this->storageDirectory)) {
            if (!mkdir($this->storageDirectory, 0755, true)) {
                throw new ImageStorageException(sprintf('Failed to create storage directory: %s', $this->storageDirectory));
            }
        }

        if (!is_writable($this->storageDirectory)) {
            throw new ImageStorageException(sprintf('Storage directory is not writable: %s', $this->storageDirectory));
        }
    }

    public function save(ImageResult $result, string $path): string
    {
        $fullPath = sprintf('%s/%s', $this->storageDirectory, trim($path, '/'));

        // Ensure the directory for the file exists
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new ImageStorageException(sprintf('Failed to create directory: %s', $directory));
            }
        }

        $resource = fopen($fullPath, 'wb');
        if (!$resource) {
            throw new ImageStorageException(sprintf('Failed to open file for writing: %s (check permissions and disk space)', $fullPath));
        }

        $content = $result->toString();
        $length = fputs($resource, $content);

        if (false === $length || $length !== strlen($content)) {
            fclose($resource);
            throw new ImageStorageException(sprintf('Failed to write image data to file: %s (check disk space and permissions)', $fullPath));
        }

        if (!fclose($resource)) {
            throw new ImageStorageException(sprintf('Failed to close file after writing: %s', $fullPath));
        }

        return $fullPath;
    }
}
