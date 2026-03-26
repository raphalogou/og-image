<?php

namespace Void\OgImage\Enum;

enum Placement: string
{
    case TopLeft = 'top-left';
    case TopCenter = 'top';
    case TopRight = 'top-right';
    case CenterLeft = 'left';
    case Center = 'center';
    case CenterRight = 'right';
    case BottomLeft = 'bottom-left';
    case BottomCenter = 'bottom';
    case BottomRight = 'bottom-right';
}
