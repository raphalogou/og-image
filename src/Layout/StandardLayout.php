<?php

namespace Void\OgImageBundle\Layout;

use Intervention\Image\Interfaces\ImageManagerInterface;
use Void\OgImageBundle\Canvas;
use Void\OgImageBundle\Enum\Placement;
use Void\OgImageBundle\Model\Background;
use Void\OgImageBundle\Model\Box\ImageBox;
use Void\OgImageBundle\Model\Box\RectBox;
use Void\OgImageBundle\Model\Box\TextBox;
use Void\OgImageBundle\Model\Font;
use Void\OgImageBundle\Model\ImageContent;
use Void\OgImageBundle\Model\Position;
use Void\OgImageBundle\Theme;

class StandardLayout extends AbstractLayout
{
    private const IMAGE_WIDTH = 1280;
    private const IMAGE_HEIGHT = 640;
    private const MAX_TITLE_LENGTH = 100;
    private const MARGIN = 50;

    // Layout spacing
    public int $spacingX = 50;
    public int $spacingY = 25;

    // Title font configuration
    public int $titleFontSize = 64;
    public string $titleFontColor = '#000000';
    public float $titleLineHeight = 1.6;

    // Text font configuration
    public int $textFontSize = 32;
    public string $textFontColor = '#000000';
    public float $textLineHeight = 2.0;

    public function defaultTheme(): Theme
    {
        $assetPath = __DIR__.'/../../assets/fonts';

        return new Theme(
            background: new Background(color: '#f1efe4'),
            primaryColor: '#033f23',
            textColor: '#000000',
            titleFont: new Font($assetPath.'/inter-bold.ttf', 64),
            bodyFont: new Font($assetPath.'/inter-regular.ttf', 28),
            badgeFont: new Font($assetPath.'/inter-semi-bold.ttf', 28),
            padding: 50,
        );
    }

    public function build(ImageManagerInterface $imageManager, ImageContent $data, ?Theme $theme = null): Canvas
    {
        $canvas = new Canvas(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, $imageManager);
        $canvas->setBackground($theme->background);

        $theme = $this->defaultTheme()->mergeWith($theme);

        // Position tracking
        $currentY = self::MARGIN;

        // Dra wthe badge
        if (!empty($data->badges)) {
            $badge = $data->badges[0];

            $badgeBox = new TextBox(
                text: $badge->value,
                font: $theme->badgeFont,
                color: $theme->badgeFont->color ?? '#ffffff',
                background: $theme->primaryColor,
                paddingX: 15,
                paddingY: 15,
            );

            $canvas->add($badgeBox, new Position(self::MARGIN, self::MARGIN));

            $currentY += $badgeBox->getSize()[1] + $this->spacingY;
        }

        // Draw title
        $title = new TextBox(
            text: $data->title,
            font: $theme->titleFont ??= new Font(__DIR__.'/../../assets/fonts/inter-bold.ttf', 32),
            color: $theme->titleFont->color ?? '#000000',
            maxWidth: self::IMAGE_WIDTH - $this->spacingX * 2,
            lineHeight: $this->titleLineHeight
        );
        $canvas->add($title, new Position($this->spacingX, $currentY));

        // Estimate title height for spacing (rough approximation)
        $estimatedTitleHeight = (int) ($theme->titleFont->size * $this->titleLineHeight * 2);
        $currentY += $estimatedTitleHeight;

        // Draw description if title is not too long
        if ($data->description && mb_strlen($data->title) <= self::MAX_TITLE_LENGTH) {
            $currentY += $this->spacingY;

            $description = new TextBox(
                text: $data->description,
                font: $theme->bodyFont ??= new Font(__DIR__.'/../../assets/fonts/inter-regular.ttf', 28),
                color: $theme->bodyFont->color ?? $this->textFontColor,
                maxWidth: self::IMAGE_WIDTH - $this->spacingX * 3,
                lineHeight: $this->textLineHeight
            );

            $canvas->add($description, new Position($this->spacingX, $currentY));
        }

        // Draw logo
        if ($theme->logo) {
            $logoBox = new ImageBox(source: $theme->logo, placement: Placement::BottomRight, scale: $theme->logoScale);
            $canvas->add($logoBox, position: new Position(self::MARGIN, (int) (self::MARGIN * 1.25)));
        }

        // Draw footer
        $footerHeight = (int) (self::MARGIN / 1.5);
        $footer = new RectBox(width: self::IMAGE_WIDTH, height: $footerHeight, fill: $theme->primaryColor ?? '#033f23');
        $canvas->add($footer, new Position(0, self::IMAGE_HEIGHT - $footerHeight));

        return $canvas;
    }
}
