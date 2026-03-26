<?php

namespace Void\OgImage\Layout;

use Intervention\Image\Interfaces\ImageManagerInterface;
use Void\OgImage\Canvas;
use Void\OgImage\Model\ImageContent;
use Void\OgImage\Theme;

abstract class AbstractLayout
{
    public function defaultTheme(): Theme
    {
        return new Theme();
    }

    abstract public function build(ImageManagerInterface $imageManager, ImageContent $data, ?Theme $theme = null): Canvas;
}
