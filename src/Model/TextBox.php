<?php

namespace Void\OgImageBundle\Model;

class TextBox extends Box
{
    public function __construct(
        public readonly string $text,
        int $x,
        int $y,
        ?int $width = null,
        ?int $height = null,
    ) {
        parent::__construct($x, $y, $width, $height);
    }
}
