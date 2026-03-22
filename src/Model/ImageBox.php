<?php

namespace Void\OgImageBundle\Model;

readonly class ImageBox extends Box
{
    public function __construct(
        public string $source,
        int $x,
        int $y,
        int $width,
        int $height,
    ) {
        parent::__construct($x, $y, $width, $height);
    }
}
