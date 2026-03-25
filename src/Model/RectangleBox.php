<?php

namespace Void\OgImageBundle\Model;

class RectangleBox extends Box
{
    public function __construct(
        public readonly string $color,
        int $x,
        int $y,
        int $width,
        int $height,
    ) {
        parent::__construct($x, $y, $width, $height);
    }
}
