<?php

namespace Void\OgImageBundle\Model;

readonly class Font
{
    public function __construct(
        public string $path,
        public ?int $size = null,
        public ?string $color = null,
    ) {
    }
}
