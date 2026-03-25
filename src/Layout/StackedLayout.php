<?php

namespace Void\OgImageBundle\Layout;

use Intervention\Image\Interfaces\ImageInterface;
use Void\OgImageBundle\Model\Content;
use Void\OgImageBundle\Model\Dimensions;
use Void\OgImageBundle\Model\RectangleBox;
use Void\OgImageBundle\Theme\Theme;

class StackedLayout extends AbstractLayout implements LayoutInterface
{
    private const IMAGE_WIDTH = 1280;
    private const IMAGE_HEIGHT = 640;
    private const BACKGROUND_SPACING = 15;
    private const PADDING_SECTION = 15;
    private const MAX_TITLE_LENGTH = 100;

    // Layout spacing
    public int $spacingX = 50;
    public int $spacingY = 50;

    // Title font configuration
    public int $titleFontSize = 64;
    public string $titleFontColor = '#000000';
    public float $titleLineHeight = 1.6;

    // Text font configuration
    public int $textFontSize = 32;
    public string $textFontColor = '#000000';
    public float $textLineHeight = 2.0;

    // Label font configuration
    public int $labelFontSize = 30;
    public string $labelFontColor = '#ffffff';
    public float $labelLineHeight = 1.0;

    public function getDimensions(): Dimensions
    {
        return new Dimensions(self::IMAGE_WIDTH, self::IMAGE_HEIGHT);
    }

    public function render(ImageInterface $image, Content $content, Theme $theme): void
    {
        $this->paintBackground($image, $theme, self::IMAGE_WIDTH, self::IMAGE_HEIGHT, $theme->backgroundPatternSpacing ?? self::BACKGROUND_SPACING);
        $this->drawFooter($image, $theme);

        // Position tracking is simple and explicit
        $nextPosition = $this->placeAtTop($this->spacingX, $this->spacingY);

        // Draw label
        if ($content->label) {
            $labelFont = $this->createLabelFont(
                $theme,
                $this->labelFontSize,
                $this->labelFontColor,
                $this->labelLineHeight,
            );
            $labelBox = $this->createTextBox($image, $content->label, $labelFont, $nextPosition->x, $nextPosition->y);

            // Add background
            $bgBox = new RectangleBox(
                $theme->primaryColor,
                $labelBox->x,
                $labelBox->y,
                $labelBox->width + self::PADDING_SECTION * 2,
                $labelBox->height + self::PADDING_SECTION * 2,
            );

            $this->renderTextBox($image, $labelBox, $labelFont, $bgBox, self::PADDING_SECTION, self::PADDING_SECTION);

            $nextPosition = $this->placeBelow($bgBox, (int) ($this->spacingY / 1.5));
        }

        // Draw title
        $titleFont = $this->createTitleFont(
            $theme,
            $this->titleFontSize,
            $this->titleFontColor,
            $this->titleLineHeight,
            self::IMAGE_WIDTH - $this->spacingX * 2,
        );
        $titleBox = $this->createTextBox($image, $content->title, $titleFont, $nextPosition->x, $nextPosition->y);
        $this->renderTextBox($image, $titleBox, $titleFont);

        // Draw description
        if ($content->description && mb_strlen($content->title) <= self::MAX_TITLE_LENGTH) {
            $nextPosition = $this->placeBelow($titleBox, $this->spacingY);
            $descFont = $this->createDefaultFont(
                $theme,
                $this->textFontSize,
                $this->textFontColor,
                $this->textLineHeight,
                self::IMAGE_WIDTH - $this->spacingX * 3,
            );
            $descBox = $this->createTextBox($image, $content->description, $descFont, $nextPosition->x, $nextPosition->y);
            $this->renderTextBox($image, $descBox, $descFont);
        }
    }

    private function drawFooter(ImageInterface $image, Theme $theme): void
    {
        $footerHeight = (int) ($this->spacingY / 1.5);

        $footerBox = new RectangleBox(
            $theme->primaryColor,
            0,
            self::IMAGE_HEIGHT - $footerHeight,
            self::IMAGE_WIDTH,
            $footerHeight,
        );

        $this->renderRectangleBox($image, $footerBox);

        $watermark = $theme->getWatermark();
        if ($watermark) {
            $watermarkImage = $image->driver()->handleInput($watermark->file);
            $watermarkImage->scaleDown((int) ($watermarkImage->width() * $watermark->scale));

            $image->place(
                element: $watermarkImage,
                position: $watermark->position,
                offset_x: $this->spacingX,
                offset_y: $footerHeight + (int) ($this->spacingY / 2),
            );
        }
    }
}
