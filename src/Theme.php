<?php

namespace Void\OgImageBundle;

use Void\OgImageBundle\Model\Background;
use Void\OgImageBundle\Model\BadgeDefinition;
use Void\OgImageBundle\Model\Font;

class Theme
{
    /**
     * @param BadgeDefinition[]|null $badges
     */
    public function __construct(
        public ?string $primaryColor = null,
        public ?Background $background = null,
        public ?string $textColor = null,
        public ?string $mutedColor = null,
        public ?Font $titleFont = null,
        public ?Font $bodyFont = null,
        public ?Font $badgeFont = null,
        public ?int $padding = null,
        public ?string $logo = null,
        public ?float $logoScale = null,
        public ?array $badges = null,
    ) {
    }

    public function mergeWith(?Theme $override): Theme
    {
        if (null === $override) {
            return $this;
        }

        return new Theme(
            primaryColor: $override->primaryColor ?? $this->primaryColor,
            background: $override->background ?? $this->background,
            textColor: $override->textColor ?? $this->textColor,
            mutedColor: $override->mutedColor ?? $this->mutedColor,
            titleFont: $override->titleFont ?? $this->titleFont,
            bodyFont: $override->bodyFont ?? $this->bodyFont,
            badgeFont: $override->badgeFont ?? $this->badgeFont,
            padding: $override->padding ?? $this->padding,
            logo: $override->logo ?? $this->logo,
            logoScale: $override->logoScale ?? $this->logoScale,
            badges: $override->badges ?? $this->badges,
        );
    }
}
