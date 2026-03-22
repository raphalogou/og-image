<?php

namespace Void\OgImageBundle\Model;

readonly class RectangleBox extends Box
{
    public function __construct(
        public string $color,
        int $x,
        int $y,
        int $width,
        int $height,
    ) {
        parent::__construct($x, $y, $width, $height);
    }
}
