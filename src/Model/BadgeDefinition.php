<?php

namespace Void\OgImage\Model;

readonly class BadgeDefinition
{
    public function __construct(
        public string $type,
        public Font $font,
        public string $color,
    ) {
    }
}
