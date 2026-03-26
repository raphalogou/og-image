<?php

namespace Void\OgImage\Model;

use Void\OgImage\Enum\Fit;

readonly class Background
{
    public function __construct(
        public ?string $color = '#f4f4f4',
        public ?string $image = null,
        public float $opacity = 10,
        public Fit $fit = Fit::Cover,
        public int $spacing = 0,
    ) {
    }
}
