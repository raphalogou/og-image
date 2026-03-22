<?php

namespace Void\OgImageBundle\Model;

readonly class Content
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $label = null,
    ) {
    }
}
