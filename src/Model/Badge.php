<?php

namespace Void\OgImageBundle\Model;

readonly class Badge
{
    public function __construct(
        public string $type,
        public string $value,
    ) {
    }
}
