<?php

namespace Void\OgImageBundle\Layout;

use Intervention\Image\Interfaces\ImageManagerInterface;
use Void\OgImageBundle\Canvas;
use Void\OgImageBundle\Model\ImageContent;
use Void\OgImageBundle\Theme;

abstract class AbstractLayout
{
    public function defaultTheme(): Theme
    {
        return new Theme();
    }

    abstract public function build(ImageManagerInterface $imageManager, ImageContent $data, ?Theme $theme = null): Canvas;
}
