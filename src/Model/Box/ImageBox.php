<?php

namespace Void\OgImageBundle\Model\Box;

use Intervention\Image\Interfaces\ImageInterface;
use Void\OgImageBundle\Enum\Placement;
use Void\OgImageBundle\Model\Position;

class ImageBox extends Box
{
    public function __construct(
        public readonly string $source,
        private ?int $width = null,
        private ?int $height = null,
        public readonly float $opacity = 1.0,
        private Placement $placement = Placement::TopLeft,
        private ?float $scale = null,
    ) {
    }

    public function setSize(int $width, int $height): static
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function setOpacity(float $opacity): static
    {
        $this->opacity = $opacity;

        return $this;
    }

    public function render(ImageInterface $image, Position $position): void
    {
        $x = $position->resolveX($image->width());
        $y = $position->resolveY($image->height());

        $elementImage = $image->driver()->handleInput($this->source);

        // Resize if size is set
        if ($this->width && $this->height) {
            $elementImage->resize($this->width, $this->height);
        }

        if ($this->scale) {
            $elementImage->scale($this->scale * $elementImage->width());
        }

        $image->place(
            element: $elementImage,
            position: $this->placement->value,
            offset_x: $x,
            offset_y: $y,
            opacity: (int) ($this->opacity * 100),
        );
    }
}
