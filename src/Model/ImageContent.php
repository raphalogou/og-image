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

    /**
     * @param array<int,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $badges = [];
        if (isset($data['badges']) && is_array($data['badges'])) {
            foreach ($data['badges'] as $badgeData) {
                if (is_array($badgeData) && isset($badgeData['type'], $badgeData['value'])) {
                    $badges[] = new Badge(
                        type: $badgeData['type'],
                        value: $badgeData['value']
                    );
                }
            }
        }

        return new self(
            title: $data['title'] ?? '',
            description: $data['description'] ?? null,
            badges: $badges,
            extras: $data['extras'] ?? [],
        );
    }
}
