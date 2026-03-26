<?php

namespace Void\OgImageBundle;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;
use Void\OgImageBundle\Enum\Fit;
use Void\OgImageBundle\Model\Background;
use Void\OgImageBundle\Model\Box\Box;
use Void\OgImageBundle\Model\Position;

class Canvas
{
    private ImageInterface $image;

    public function __construct(private readonly int $width, private readonly int $height, ImageManagerInterface $imageManager)
    {
        $this->image = $imageManager
            ->create($this->width, $this->height)
            ->fill('#ffffff');
    }

    public function setBackground(Background $background): void
    {
        $this->image->fill($background->color ?? '#ffffff');

        if ($background->image) {
            $this->applyImageBackground($background, $background->fit);
        }
    }

    private function applyImageBackground(Background $background, Fit $fit): void
    {
        $bgImage = $this->image->driver()->handleInput($background->image);

        match ($fit) {
            Fit::Cover => $this->applyCover($bgImage),
            Fit::Contain => $this->applyContain($bgImage),
            Fit::Center => $this->applyCenter($bgImage),
            Fit::Repeat => $this->applyRepeat($bgImage, $background),
            Fit::Stretch => $this->applyStretch($bgImage),
        };
    }

    private function applyCover(ImageInterface $bgImage): void
    {
        $aspectCanvas = $this->width / $this->height;
        $aspectBg = $bgImage->width() / $bgImage->height();

        if ($aspectBg > $aspectCanvas) {
            // Image is wider, fit height
            $bgImage->scaleDown(height: $this->height);
        } else {
            // Image is taller, fit width
            $bgImage->scaleDown(width: $this->width);
        }

        $this->image->place($bgImage, 'center');
    }

    private function applyContain(ImageInterface $bgImage): void
    {
        $bgImage->cover($this->width, $this->height);
        $this->image->place($bgImage, 'center');
    }

    private function applyCenter(ImageInterface $bgImage): void
    {
        $this->image->place($bgImage, 'center');
    }

    private function applyRepeat(ImageInterface $bgImage, Background $backgroundImg): void
    {
        $bgWidth = $bgImage->width();
        $bgHeight = $bgImage->height();

        for ($x = $backgroundImg->spacing; $x < $this->width; $x += $bgWidth + $backgroundImg->spacing) {
            for ($y = $backgroundImg->spacing; $y < $this->height; $y += $bgHeight + $backgroundImg->spacing) {
                $this->image->place(
                    element: clone $bgImage,
                    position: 'top-left',
                    offset_x: $x,
                    offset_y: $y,
                    opacity: (int) ($backgroundImg->opacity * 100)
                );
            }
        }
    }

    private function applyStretch(ImageInterface $bgImage): void
    {
        $bgImage->resize($this->width, $this->height);
        $this->image->place($bgImage, 'top-left');
    }

    public function add(Box $box, Position $position): void
    {
        $box->render($this->image, $position);
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return array<int,int>
     */
    public function getSize(): array
    {
        return [$this->width, $this->height];
    }

    public function getImage(): ImageInterface
    {
        return $this->image;
    }
}
