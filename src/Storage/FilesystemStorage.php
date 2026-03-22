<?php

namespace Void\OgImageBundle\Storage;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemStorage implements StorageInterface
{
    private readonly Filesystem $filesystem;
    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly string $storageDirectory,
        ?LoggerInterface $logger = null,
    ) {
        $this->filesystem = new Filesystem();
        $this->logger = $logger ?? new NullLogger();

        if (false === $this->filesystem->exists($this->storageDirectory)) {
            $this->filesystem->mkdir($this->storageDirectory);
        }
    }

    public function saveImage(
        EncodedImageInterface $image,
        string $path,
        ?string $directory = null,
    ): ?string {
        $path = sprintf(
            "%s/%s",
            $this->storageDirectory,
            sprintf(
                "%s/%s",
                $directory ? trim($directory, "/") : "",
                trim($path, "/"),
            ),
        );

        try {
            $this->filesystem->dumpFile($path, $image->toString());

            return $path;
        } catch (IOException $exception) {
            $this->logger->error("Failed to save open graph image", [
                "exception" => $exception,
            ]);

            return null;
        }
    }
}
