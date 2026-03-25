<?php

namespace Void\OgImageBundle\Model;

class ImageBox extends Box
{
    public function __construct(
        public readonly string $source,
        int $x,
        int $y,
        ?int $width = null,
        ?int $height = null,
        public ?string $position = null,
    ) {
        parent::__construct($x, $y, $width, $height);
    }
}
