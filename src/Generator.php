<?php

namespace Void\OgImageBundle;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;
use Void\OgImageBundle\Layout\LayoutInterface;
use Void\OgImageBundle\Model\Content;
use Void\OgImageBundle\Theme\Theme;

class Generator
{
    private readonly ImageManagerInterface $imageManager;

    public function __construct()
    {
        $this->imageManager = ImageManager::gd();
    }

    public function generate(
        Content $content,
        Theme $theme,
        LayoutInterface $layout,
    ): EncodedImageInterface {
        $dimensions = $layout->getDimensions();
        $background = $theme->getBackground();

        $image = $this->imageManager
            ->create($dimensions->width, $dimensions->height)
            ->fill($background?->color ?? '#ffffff');

        $layout->render($image, $content, $theme);

        return $image->toWebp();
    }
}
