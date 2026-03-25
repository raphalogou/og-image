<?php

namespace Void\OgImageBundle\Storage;

use Symfony\Component\Filesystem\Filesystem;
use Void\OgImageBundle\Exception\ImageStorageException;
use Void\OgImageBundle\ImageResult;

class FilesystemStorage implements StorageInterface
{
    private readonly Filesystem $filesystem;

    public function __construct(private readonly string $storageDirectory)
    {
        $this->filesystem = new Filesystem();

        if (false === $this->filesystem->exists($this->storageDirectory)) {
            $this->filesystem->mkdir($this->storageDirectory);
        }
    }

    public function save(ImageResult $result, string $path): string
    {
        $fullPath = sprintf('%s/%s', $this->storageDirectory, trim($path, '/'));

        try {
            $this->filesystem->dumpFile($fullPath, $result->toString());

            return $fullPath;
        } catch (\Exception $e) {
            throw new ImageStorageException(sprintf('Failed to save image to %s: %s', $fullPath, $e->getMessage()), 0, $e);
        }
    }
}
