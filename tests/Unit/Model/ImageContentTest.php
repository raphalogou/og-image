<?php

namespace Void\OgImage\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Void\OgImage\Model\Badge;
use Void\OgImage\Model\ImageContent;

class ImageContentTest extends TestCase
{
    public function testConstructorWithMinimalData(): void
    {
        $content = new ImageContent(title: 'Test Title');

        $this->assertSame('Test Title', $content->title);
        $this->assertNull($content->description);
        $this->assertSame([], $content->badges);
        $this->assertSame([], $content->extras);
    }

    public function testConstructorWithFullData(): void
    {
        $badges = [
            new Badge('category', 'Tech'),
            new Badge('status', 'Published'),
        ];

        $extras = ['author' => 'John Doe', 'date' => '2024-01-01'];

        $content = new ImageContent(
            title: 'Full Test',
            description: 'Full description',
            badges: $badges,
            extras: $extras
        );

        $this->assertSame('Full Test', $content->title);
        $this->assertSame('Full description', $content->description);
        $this->assertSame($badges, $content->badges);
        $this->assertSame($extras, $content->extras);
    }
}
