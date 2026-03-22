<?php

namespace Void\OgImageBundle\Model;

readonly class Watermark
{
    public function __construct(
        public string $file,
        public string $position,
        public float $scale,
    ) {
    }
}
