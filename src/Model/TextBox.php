<?php

namespace Void\OgImageBundle\Model;

readonly class TextBox extends Box
{
    public function __construct(
        public string $text,
        int $x,
        int $y,
        int $width,
        int $height,
    ) {
        parent::__construct($x, $y, $width, $height);
    }
}
