<?php

namespace Void\OgImageBundle;

use Void\OgImageBundle\Enum\Format;
use Void\OgImageBundle\Layout\Layout;
use Void\OgImageBundle\Model\ImageContent;

class Generator
{
    public function generate(ImageContent $data, Layout $layout, ?Theme $theme = null, Format $format = Format::Webp): ImageResult
    {
        $resolvedTheme = $layout
            ->defaultTheme()
            ->mergeWith($theme);

        $canvas = $layout->build($data, $resolvedTheme);

        // 3. Encode based on format
        $image = $canvas->getImage();
        $encoded = match ($format) {
            Format::Png => $image->toPng(),
            Format::Webp => $image->toWebp(),
        };

        // 4. Return ImageResult
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
