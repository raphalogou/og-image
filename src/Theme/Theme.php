<?php

namespace Void\OgImageBundle\Theme;

use Void\OgImageBundle\Model\Background;
use Void\OgImageBundle\Model\Watermark;

class Theme
{
    // Colors
    public string $primaryColor = '#033f23';
    public string $backgroundColor = '#f1efe4';
    public int $backgroundOpacity = 6;
    public ?string $backgroundImage = null;

    // Watermark
    public ?string $watermarkFile = null;
    public float $watermarkScale = 0.5;
    public string $watermarkPosition = 'bottom-right';

    // Font files
    public string $titleFontFile;
    public string $textFontFile;
    public string $labelFontFile;

    // Cached models
    private ?Watermark $watermark = null;
    private ?Background $background = null;

    public function __construct(
        string $primaryColor = '#033f23',
        string $backgroundColor = '#f1efe4',
        int $backgroundOpacity = 6,
        ?string $backgroundImage = null,
        ?string $watermarkFile = null,
        float $watermarkScale = 0.5,
        string $watermarkPosition = 'bottom-right',
        ?string $titleFontFile = null,
        ?string $textFontFile = null,
        ?string $labelFontFile = null,
    ) {
        $this->primaryColor = $primaryColor;
        $this->backgroundColor = $backgroundColor;
        $this->backgroundOpacity = $backgroundOpacity;
        $this->backgroundImage = $backgroundImage;
        $this->watermarkFile = $watermarkFile;
        $this->watermarkScale = $watermarkScale;
        $this->watermarkPosition = $watermarkPosition;

        // Set default font paths
        $assetPath = __DIR__.'/../../assets/fonts';
        $this->titleFontFile = $titleFontFile ?? $assetPath.'/inter-bold.ttf';
        $this->textFontFile = $textFontFile ?? $assetPath.'/inter-regular.ttf';
        $this->labelFontFile = $labelFontFile ?? $assetPath.'/inter-semi-bold.ttf';
    }

    public function getPrimaryColor(): string
    {
        return $this->primaryColor;
    }

    public function getBackground(): ?Background
    {
        if (null === $this->background && null !== $this->backgroundImage) {
            $this->background = new Background(
                color: $this->backgroundColor,
                image: $this->backgroundImage,
                opacity: $this->backgroundOpacity,
            );
        }

        return $this->background;
    }

    public function getWatermark(): ?Watermark
    {
        if (null === $this->watermark && null !== $this->watermarkFile) {
            $this->watermark = new Watermark(
                file: $this->watermarkFile,
                position: $this->watermarkPosition,
                scale: $this->watermarkScale,
            );
        }

        return $this->watermark;
    }
}
