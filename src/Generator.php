<?php

namespace Void\OgImageBundle;

use Intervention\Image\Interfaces\ImageManagerInterface;
use Void\OgImageBundle\Enum\Format;
use Void\OgImageBundle\Layout\AbstractLayout;
use Void\OgImageBundle\Model\ImageContent;

class Generator
{
    public function __construct(private readonly ImageManagerInterface $imageManager)
    {
    }

    public function generate(ImageContent $data, AbstractLayout $layout, ?Theme $theme = null, Format $format = Format::Webp): ImageResult
    {
        $resolvedTheme = $layout
            ->defaultTheme()
            ->mergeWith($theme);

        $canvas = $layout->build($this->imageManager, $data, $resolvedTheme);

        $image = $canvas->getImage();
        $encoded = match ($format) {
            Format::Png => $image->toPng(),
            Format::Webp => $image->toWebp(),
        };

        return new ImageResult(
            $encoded,
            $canvas->getWidth(),
            $canvas->getHeight(),
            match ($format) {
                Format::Png => 'image/png',
                Format::Webp => 'image/webp',
            }
        );
    }
}
