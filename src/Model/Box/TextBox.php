<?php

namespace Void\OgImageBundle\Model\Box;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Typography\Font as InterventionFont;
use Void\OgImageBundle\Enum\Overflow;
use Void\OgImageBundle\Enum\TextAlignment;
use Void\OgImageBundle\Model\Font;
use Void\OgImageBundle\Model\Position;

class TextBox extends Box
{
    public function __construct(
        public readonly string $text,
        private ?Font $font = null,
        private ?string $color = null,
        private ?int $maxWidth = null,
        private float $lineHeight = 1.0,
        private TextAlignment $alignment = TextAlignment::Left,
        private Overflow $overflow = Overflow::Wrap,
        private ?string $background = null,
        private int $paddingX = 0,
        private int $paddingY = 0,
    ) {
    }

    public function setFont(Font $font): static
    {
        $this->font = $font;

        return $this;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function setMaxWidth(int $width): static
    {
        $this->maxWidth = $width;

        return $this;
    }

    public function setLineHeight(float $height): static
    {
        $this->lineHeight = $height;

        return $this;
    }

    public function setOverflow(Overflow $overflow): static
    {
        $this->overflow = $overflow;

        return $this;
    }

    public function render(ImageInterface $image, Position $position): void
    {
        if (null === $this->font) {
            throw new \RuntimeException('Font must be set before rendering TextBox');
        }

        $x = $position->resolveX($image->width());
        $y = $position->resolveY($image->height());

        $interventionFont = new InterventionFont($this->font->path);
        $interventionFont->setSize($this->font->size)
            ->setColor($this->color ?? '#000000')
            ->setLineHeight($this->lineHeight)
            ->setValignment('top')
            ->setAlignment($this->alignment->value)
        ;

        if ($this->maxWidth) {
            $interventionFont->setWrapWidth($this->maxWidth);
        }

        $text = $this->text;

        // Handle overflow
        if (Overflow::Truncate === $this->overflow && $this->maxWidth) {
            $text = $this->truncateText($image, $text, $interventionFont);
        } elseif (Overflow::Shrink === $this->overflow && $this->maxWidth) {
            $interventionFont = $this->shrinkFont($image, $text, $interventionFont);
        }

        if ($this->background) {
            [$width, $height] = $this->computeTextSize($image, $interventionFont);
            $this->size = [$width + $this->paddingX * 2, $height + $this->paddingY * 2];

            $backgroundBox = new RectBox(width: $this->size[0], height: $this->size[1], fill: $this->background);
            $backgroundBox->render($image, $position);

            $x += $this->paddingX;
            $y += $this->paddingY;
        }

        $image->text($text, $x, $y, $interventionFont);
    }

    /**
     * @return array<int,int>
     */
    private function computeTextSize(ImageInterface $image, InterventionFont $font): array
    {
        if (!$this->size) {
            $fontProcessor = $image->driver()->fontProcessor();
            $textBlock = $fontProcessor->textBlock($this->text, $font, new Point());

            $width = $fontProcessor->boxSize((string) $textBlock->longestLine(), $font)->width();
            $height = $fontProcessor->leading($font) * ($textBlock->count() - 1) + $fontProcessor->capHeight($font);

            $this->size = [$width, $height];
        }

        return $this->size;
    }

    private function truncateText(ImageInterface $image, string $text, InterventionFont $font): string
    {
        [$width, $height] = $this->computeTextSize($image, $font);

        if ($width <= $this->maxWidth) {
            return $text;
        }

        // Truncate with ellipsis
        $ellipsis = '...';
        $words = explode(' ', $text);
        $truncated = '';
        $fontProcessor = $image->driver()->fontProcessor();

        foreach ($words as $word) {
            $testText = $truncated.$word.' '.$ellipsis;
            $testWidth = $fontProcessor->boxSize($testText, $font)->width();

            if ($testWidth > $this->maxWidth) {
                return trim($truncated).$ellipsis;
            }

            $truncated .= $word.' ';
        }

        return trim($truncated);
    }

    private function shrinkFont(ImageInterface $image, string $text, InterventionFont $font): InterventionFont
    {
        $fontProcessor = $image->driver()->fontProcessor();
        $originalSize = $this->font->size;
        $originalBoxSize = $this->computeTextSize($image, $font);

        for ($size = $originalSize; $size >= 8; --$size) {
            $testFont = clone $font;
            $testFont->setSize($size);
            [$width] = $this->computeTextSize($image, $testFont);

            if ($width <= $this->maxWidth) {
                return $testFont;
            }
        }

        $this->size = $originalBoxSize;

        return $font;
    }
}
