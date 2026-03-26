<?php

namespace Void\OgImage\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Void\OgImage\Model\Background;
use Void\OgImage\Model\Font;
use Void\OgImage\Theme;

class ThemeTest extends TestCase
{
    public function testMergeWithOverridesValues(): void
    {
        $base = new Theme(
            primaryColor: '#ff0000',
            textColor: '#000000',
            padding: 20
        );

        $override = new Theme(
            primaryColor: '#00ff00',
            padding: 40
        );

        $merged = $base->mergeWith($override);

        $this->assertSame('#00ff00', $merged->primaryColor);
        $this->assertSame('#000000', $merged->textColor);
        $this->assertSame(40, $merged->padding);
    }

    public function testMergeWithNullReturnsOriginal(): void
    {
        $theme = new Theme(primaryColor: '#ff0000');

        $merged = $theme->mergeWith(null);

        $this->assertSame($theme, $merged);
    }

    public function testMergeWithPreservesBaseWhenOverrideIsNull(): void
    {
        $background = new Background(color: '#ffffff');
        $font = new Font('/fonts/title.ttf', 48);

        $base = new Theme(
            background: $background,
            titleFont: $font,
            padding: 20
        );

        $override = new Theme(primaryColor: '#00ff00');

        $merged = $base->mergeWith($override);

        $this->assertSame('#00ff00', $merged->primaryColor);
        $this->assertSame($background, $merged->background);
        $this->assertSame($font, $merged->titleFont);
        $this->assertSame(20, $merged->padding);
    }
}
