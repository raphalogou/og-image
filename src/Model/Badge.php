<?php

namespace Void\OgImage\Model;

readonly class Badge
{
    public function __construct(
        public string $type,
        public string $value,
    ) {
    }
}
