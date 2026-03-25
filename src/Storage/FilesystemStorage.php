<?php

namespace Void\OgImageBundle\Storage;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Symfony\Component\Filesystem\Filesystem;
use Void\OgImageBundle\Exception\ImageStorageException;

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

    public function saveImage(EncodedImageInterface $image, string $path): string
    {
        $path = sprintf('%s/%s', $this->storageDirectory, trim($path, '/'));

        try {
            $this->filesystem->dumpFile($path, $image->toString());

            return $path;
        } catch (\Exception $e) {
            throw new ImageStorageException(sprintf('Failed to save image to %s: %s', $path, $e->getMessage()), 0, $e);
        }
    }
}
