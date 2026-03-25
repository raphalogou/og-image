<?php

namespace Void\OgImageBundle\Layout;

use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Typography\Font;
use Void\OgImageBundle\Model\Box;
use Void\OgImageBundle\Model\ImageBox;
use Void\OgImageBundle\Model\RectangleBox;
use Void\OgImageBundle\Model\TextBox;
use Void\OgImageBundle\Theme\Theme;

abstract class AbstractLayout implements LayoutInterface
{
    /**
     * Place a box at the top-left starting position.
     */
    protected function placeAtTop(int $x, int $y): Box
    {
        return new Box($x, $y, 0, 0);
    }

    /**
     * Place a box below another box with optional gap.
     */
    protected function placeBelow(Box $box, int $spacing = 0): Box
    {
        return new Box($box->x, $box->bottom() + $spacing, 0, 0);
    }

    /**
     * Place a box to the right of another box with optional gap.
     */
    protected function placeRightOf(Box $box, int $spacing = 0): Box
    {
        return new Box($box->right() + $spacing, $box->y, 0, 0);
    }

    /**
     * Measure text and return a TextBox with actual dimensions.
     */
    protected function createTextBox(
        ImageInterface $image,
        string $text,
        FontInterface $font,
        int $x = 0,
        int $y = 0,
    ): TextBox {
        $fontProcessor = $image->driver()->fontProcessor();
        $driverTextBlock = $fontProcessor->textBlock($text, $font, new Point());

        $width = $fontProcessor
            ->boxSize((string) $driverTextBlock->longestLine(), $font)
            ->width();
        $height =
            $fontProcessor->leading($font) * ($driverTextBlock->count() - 1) +
            $fontProcessor->capHeight($font);

        return new TextBox($text, $x, $y, (int) $width, (int) $height);
    }

    /**
     * Create a font with the given configuration.
     */
    protected function createFont(
        string $fontFile,
        int $size,
        string $color,
        float $lineHeight,
        ?int $wrapWidth = null,
    ): Font {
        $font = new Font($fontFile)
            ->setSize($size)
            ->setValignment('top')
            ->setColor($color)
            ->setLineHeight($lineHeight);

        if ($wrapWidth) {
            $font->setWrapWidth($wrapWidth);
        }

        return $font;
    }

    /**
     * Create a title font using theme and layout configuration.
     */
    protected function createTitleFont(
        Theme $theme,
        int $size = 64,
        string $color = '#000000',
        float $lineHeight = 1.6,
        ?int $wrapWidth = null,
    ): Font {
        return $this->createFont(
            $theme->titleFontFile,
            $size,
            $color,
            $lineHeight,
            $wrapWidth,
        );
    }

    /**
     * Create a text font using theme and layout configuration.
     */
    protected function createDefaultFont(
        Theme $theme,
        int $size = 32,
        string $color = '#000000',
        float $lineHeight = 2.0,
        ?int $wrapWidth = null,
    ): Font {
        return $this->createFont(
            $theme->textFontFile,
            $size,
            $color,
            $lineHeight,
            $wrapWidth,
        );
    }

    /**
     * Create a label font using theme and layout configuration.
     */
    protected function createLabelFont(
        Theme $theme,
        int $size = 30,
        string $color = '#ffffff',
        float $lineHeight = 1.0,
        ?int $wrapWidth = null,
    ): Font {
        return $this->createFont(
            $theme->labelFontFile,
            $size,
            $color,
            $lineHeight,
            $wrapWidth,
        );
    }

    /**
     * Render a text box with optional background.
     */
    protected function renderTextBox(
        ImageInterface $image,
        TextBox $box,
        FontInterface $font,
        ?RectangleBox $background = null,
        int $paddingX = 0,
        int $paddingY = 0,
    ): void {
        if ($background) {
            $this->renderRectangleBox($image, $background);
            $image->text(
                $box->text,
                $box->x + $paddingX,
                $box->y + $paddingY,
                $font,
            );
        } else {
            $image->text($box->text, $box->x, $box->y, $font);
        }
    }

    /**
     * Render a rectangle box.
     */
    protected function renderRectangleBox(
        ImageInterface $image,
        RectangleBox $box,
    ): void {
        $image->drawRectangle($box->x, $box->y, function (
            RectangleFactory $factory,
        ) use ($box) {
            $factory->size($box->width, $box->height)->background($box->color);
        });
    }

    /**
     * Render an image box.
     */
    protected function renderImageBox(
        ImageInterface $image,
        ImageBox $box,
        ?float $scale = null,
    ): void {
        $elementImage = $image->driver()->handleInput($box->source);
        if ($scale) {
            $elementImage->scaleDown((int) ($elementImage->width() * $scale));
        }

        $image->place(
            element: $elementImage,
            position: $box->position ?? 'top-left',
            offset_x: $box->x,
            offset_y: $box->y,
        );

        $box->setDimensions(width: $elementImage->width(), height: $elementImage->height());
    }

    /**
     * Paint background pattern from theme.
     */
    protected function paintBackground(
        ImageInterface $image,
        Theme $theme,
        ?int $imageWidth = null,
        ?int $imageHeight = null,
        int $spacing = 0,
    ): void {
        $background = $theme->getBackground();

        if (!$background || !$background->image) {
            return;
        }

        $backgroundImage = $image->driver()->handleInput($background->image);
        $bgWidth = $backgroundImage->width();
        $bgHeight = $backgroundImage->height();
        $opacity = $background->opacity;

        $imageWidth ??= $image->width();
        $imageHeight ??= $image->height();

        for ($x = $spacing; $x < $imageWidth; $x += $bgWidth + $spacing) {
            for ($y = $spacing; $y < $imageHeight; $y += $bgHeight + $spacing) {
                $image->place(
                    element: $backgroundImage,
                    offset_x: $x,
                    offset_y: $y,
                    opacity: $opacity,
                );
            }
        }
    }
}
