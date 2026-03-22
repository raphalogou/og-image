<?php

namespace Void\OgImageBundle\Model;

readonly class Background
{
    public function __construct(
        public ?string $color = '#f4f4f4',
        public ?string $image = null,
        public ?float $opacity = 10,
    ) {
    }
}
