<?php

namespace Void\OgImageBundle\Model;

readonly class Dimensions
{
    public function __construct(
        public int $width,
        public int $height,
    ) {
    }
}
