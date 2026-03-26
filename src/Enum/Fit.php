<?php

namespace Void\OgImage\Enum;

enum Fit
{
    case Cover;     // fills canvas, crops if needed — default for ImageBox backgrounds
    case Contain;   // fits inside canvas, may leave space
    case Center;    // original size, centered
    case Repeat;    // tiled pattern
    case Stretch;   // forces exact canvas dimensions
}
