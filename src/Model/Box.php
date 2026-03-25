<?php

namespace Void\OgImageBundle\Model;

class Box
{
    public function __construct(
        public int $x,
        public int $y,
        public ?int $width,
        public ?int $height,
    ) {
    }

    public function right(): int
    {
        return $this->x + ($this->width ?? 0);
    }

    public function bottom(): int
    {
        return $this->y + ($this->height ?? 0);
    }

    public function setDimensions(int $width, int $height): void
    {
        $this->width = $width;
        $this->height = $height;
    }
}
