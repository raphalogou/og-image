<?php

namespace Void\OgImageBundle\Model;

readonly class Position
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }
}
