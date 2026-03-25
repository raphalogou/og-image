<?php

namespace Void\OgImageBundle\Layout;

use Void\OgImageBundle\Canvas;
use Void\OgImageBundle\Model\ImageContent;
use Void\OgImageBundle\Theme;

abstract class AbstractLayout
{
    abstract public function build(ImageContent $data, Theme $theme): Canvas;

    public function defaultTheme(): Theme
    {
        return new Theme();
    }
}
