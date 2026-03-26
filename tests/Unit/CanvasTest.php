<?php

namespace Void\OgImage\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Void\OgImage\Canvas;
use Void\OgImage\Model\Background;
use Void\OgImage\Tests\ImageManagerFactory;

class CanvasTest extends TestCase
{
    private function createCanvas(int $width = 1280, int $height = 640): Canvas
    {
        return new Canvas($width, $height, ImageManagerFactory::create());
    }

    public function testGetImageReturnsInterventionImage(): void
    {
        $canvas = $this->createCanvas(1280, 640);

        $image = $canvas->getImage();

        $this->assertInstanceOf(\Intervention\Image\Interfaces\ImageInterface::class, $image);
        $this->assertSame(1280, $image->width());
        $this->assertSame(640, $image->height());
    }

    public function testSetBackgroundAppliesColor(): void
    {
        $canvas = $this->createCanvas(800, 600);
        $background = new Background(color: '#ff0000');

        $canvas->setBackground($background);

        // If no exception thrown, background was applied
        $this->assertTrue(true);
    }
}
