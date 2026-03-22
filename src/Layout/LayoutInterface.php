<?php

namespace Void\OgImageBundle\Layout;

use Intervention\Image\Interfaces\ImageInterface;
use Void\OgImageBundle\Model\Content;
use Void\OgImageBundle\Model\Dimensions;
use Void\OgImageBundle\Theme\Theme;

interface LayoutInterface
{
    /**
     * Get the dimensions (width x height) for this layout.
     */
    public function getDimensions(): Dimensions;

    /**
     * Render the content onto the image using the theme.
     */
    public function render(
        ImageInterface $image,
        Content $content,
        Theme $theme,
    ): void;
}
