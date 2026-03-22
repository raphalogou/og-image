<?php

namespace Void\OgImageBundle\Model;

readonly class TextFont
{
    public function __construct(
        public string $file,
        public int $size,
        public float $lineHeight,
        public string $color = '#000000',
        public ?int $wrapWidth = null,
    ) {
    }
}
