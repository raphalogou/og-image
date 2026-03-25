<?php

namespace Void\OgImageBundle\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Void\OgImageBundle\Enum\Format;
use Void\OgImageBundle\Generator;
use Void\OgImageBundle\Layout\StandardLayout;
use Void\OgImageBundle\Model\Background;
use Void\OgImageBundle\Model\Badge;
use Void\OgImageBundle\Model\Font;
use Void\OgImageBundle\Model\ImageContent;
use Void\OgImageBundle\Theme;

class GeneratorTest extends TestCase
{
    private Generator $generator;
    private string $fontPath;

    protected function setUp(): void
    {
        $this->generator = new Generator();
        $this->fontPath = __DIR__.'/../../assets/fonts/inter-regular.ttf';

        // Verify font file exists
        if (!file_exists($this->fontPath)) {
            $this->markTestSkipped('Font file not found at: '.$this->fontPath);
        }
    }

    public function testGenerateBasicImage(): void
    {
        $content = new ImageContent(
            title: 'Test Title',
            description: 'Test Description'
        );

        $layout = new StandardLayout();
        $result = $this->generator->generate($content, $layout);

        $this->assertSame(1280, $result->getWidth());
        $this->assertSame(640, $result->getHeight());
        $this->assertSame('image/webp', $result->mimeType());
        $this->assertNotEmpty($result->toString());
    }

    public function testGenerateWithCustomTheme(): void
    {
        $content = new ImageContent(title: 'Custom Theme Test');

        $theme = new Theme(
            primaryColor: '#ff0000',
            background: new Background(color: '#ffffff'),
            titleFont: new Font($this->fontPath, 64, '#000000'),
            padding: 50
        );

        $layout = new StandardLayout();
        $result = $this->generator->generate($content, $layout, $theme);

        $this->assertSame(1280, $result->getWidth());
        $this->assertSame(640, $result->getHeight());
        $this->assertNotEmpty($result->toString());
    }

    public function testGenerateWithBadges(): void
    {
        $content = new ImageContent(
            title: 'Badge Test',
            description: 'Testing badges',
            badges: [
                new Badge('category', 'Technology'),
                new Badge('status', 'Published'),
            ]
        );

        $layout = new StandardLayout();
        $result = $this->generator->generate($content, $layout);

        $this->assertNotEmpty($result->toString());
    }

    public function testGenerateWithPngFormat(): void
    {
        $content = new ImageContent(title: 'PNG Format Test');

        $layout = new StandardLayout();
        $result = $this->generator->generate($content, $layout, null, Format::Png);

        $this->assertSame('image/png', $result->mimeType());
        $this->assertNotEmpty($result->toString());
    }

    public function testGenerateWithWebpFormat(): void
    {
        $content = new ImageContent(title: 'WebP Format Test');

        $layout = new StandardLayout();
        $result = $this->generator->generate($content, $layout, null, Format::Webp);

        $this->assertSame('image/webp', $result->mimeType());
        $this->assertNotEmpty($result->toString());
    }
}
