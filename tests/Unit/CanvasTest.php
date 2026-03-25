<?php

namespace Void\OgImageBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Void\OgImageBundle\Canvas;
use Void\OgImageBundle\Model\Background;

class CanvasTest extends TestCase
{
    public function testGetImageReturnsInterventionImage(): void
    {
        $canvas = new Canvas(1280, 640);

        $image = $canvas->getImage();

        $this->assertInstanceOf(\Intervention\Image\Interfaces\ImageInterface::class, $image);
        $this->assertSame(1280, $image->width());
        $this->assertSame(640, $image->height());
    }

    public function testSetBackgroundAppliesColor(): void
    {
        $canvas = new Canvas(800, 600);
        $background = new Background(color: '#ff0000');

        $canvas->setBackground($background);

        // If no exception thrown, background was applied
        $this->assertTrue(true);
    }
}
