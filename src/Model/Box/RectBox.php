<?php

namespace Void\OgImageBundle\Model\Box;

use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Interfaces\ImageInterface;
use Void\OgImageBundle\Model\Position;

class RectBox extends Box
{
    public function __construct(
        public readonly int $width,
        public readonly int $height,
        private ?string $fill = null,
    ) {
    }

    public function setFill(string $color): static
    {
        $this->fill = $color;

        return $this;
    }

    public function render(ImageInterface $image, Position $position): void
    {
        $x = $position->resolveX($image->width());
        $y = $position->resolveY($image->height());

        $image->drawRectangle($x, $y, function (RectangleFactory $factory) {
            $factory->size($this->width, $this->height)
                ->background($this->fill ?? '#000000');
        });
    }
}
