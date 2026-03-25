<?php

namespace Void\OgImageBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Void\OgImageBundle\Model\Position;

class PositionTest extends TestCase
{
    public function testResolveXWithPercentage(): void
    {
        $position = new Position(x: '50%', y: 0);

        $this->assertSame(640, $position->resolveX(1280));
        $this->assertSame(500, $position->resolveX(1000));
    }

    public function testResolveYWithPercentage(): void
    {
        $position = new Position(x: 0, y: '25%');

        $this->assertSame(160, $position->resolveY(640));
        $this->assertSame(250, $position->resolveY(1000));
    }

    public function testResolveWithAbsoluteValues(): void
    {
        $position = new Position(x: 100, y: 200);

        $this->assertSame(100, $position->resolveX(1280));
        $this->assertSame(200, $position->resolveY(640));
    }
}
