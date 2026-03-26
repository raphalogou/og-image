<?php

namespace Void\OgImage\Model\Box;

use Intervention\Image\Interfaces\ImageInterface;
use Void\OgImage\Model\Position;

abstract class Box
{
    protected ?array $size = null;

    abstract public function render(ImageInterface $image, Position $position): void;

    /**
     * @return array<int,int>
     */
    public function getSize(): array
    {
        if (!$this->size) {
            throw new \RuntimeException('You cannot get a Box size before its rendering');
        }

        return $this->size;
    }
}
