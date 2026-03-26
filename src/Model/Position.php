<?php

namespace Void\OgImage\Model;

readonly class Position
{
    public function __construct(
        public int|string $x,  // int (pixels) or string percentage ('50%')
        public int|string $y,
    ) {
    }

    public function resolveX(int $canvasWidth): int
    {
        if (is_string($this->x)) {
            return (int) (($canvasWidth * (float) rtrim($this->x, '%')) / 100);
        }

        return $this->x;
    }

    public function resolveY(int $canvasHeight): int
    {
        if (is_string($this->y)) {
            return (int) (($canvasHeight * (float) rtrim($this->y, '%')) / 100);
        }

        return $this->y;
    }
}
