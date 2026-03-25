<?php

namespace Void\OgImageBundle\Model;

readonly class ImageContent
{
    /**
     * @param Badge[]              $badges
     * @param array<string, mixed> $extras
     */
    public function __construct(
        public string $title,
        public ?string $description = null,
        public array $badges = [],
        public array $extras = [],
    ) {
    }
}
