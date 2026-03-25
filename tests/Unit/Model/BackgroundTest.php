<?php

namespace Void\OgImageBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Void\OgImageBundle\Model\Background;

class BackgroundTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $background = new Background();

        $this->assertSame('#f4f4f4', $background->color);
        $this->assertNull($background->image);
    }
}
