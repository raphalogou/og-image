<?php

namespace Void\OgImage;

use Intervention\Image\Interfaces\EncodedImageInterface;

class OpenGraphImage
{
    public function __construct(
        private readonly EncodedImageInterface $encoded,
        private readonly int $width,
        private readonly int $height,
        private readonly string $mimeType,
    ) {
    }

    /**
     * @return resource
     */
    public function toStream()
    {
        return $this->encoded->toFilePointer();
    }

    public function toBase64(): string
    {
        return $this->encoded->toDataUri();
    }

    public function toString(): string
    {
        return $this->encoded->toString();
    }

    public function mimeType(): string
    {
        return $this->mimeType;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
