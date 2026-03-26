# OG Image

A standalone PHP library for generating dynamic Open Graph (OG) images, with optional Symfony bundle integration.

## Overview

OG Image provides a flexible, extensible system to programmatically generate custom Open Graph images. Perfect for creating dynamic social media preview images with titles, descriptions, badges, and custom themes.

**Works standalone** or as a **Symfony bundle** - use it however you prefer!

## Features

- 🎨 **Customizable Themes** - Define colors, backgrounds, and fonts
- 📐 **Flexible Layout System** - Built-in layouts and easy to extend
- 🏷️ **Badge Support** - Add category/status badges to your images
- 🔄 **Multiple Formats** - Render to WebP (default) or PNG
- 🚀 **Simple API** - Intuitive, functional approach with immediate rendering
- 🔌 **Zero Dependencies** - Core library has no external dependencies (except Intervention Image)
- 🎯 **Standalone & Bundle** - Use as a library or register as a Symfony bundle

## Requirements

- PHP 8.2+
- Intervention Image 3.11+
- **Image Driver**: One of:
  - **GD** (bundled with PHP, recommended)
  - **Imagick** (optional, for better performance)

**Optional (for Symfony integration):**
- Symfony 7.4 or 8.0+

At least one image driver must be available. GD is included with most PHP installations by default.

## Installation

### Via Composer

```bash
composer require void/og-image
```

## Quick Start - Standalone

```php
use Intervention\Image\ImageManager;
use Void\OgImage\Generator;
use Void\OgImage\Model\ImageContent;
use Void\OgImage\Layout\StandardLayout;
use Void\OgImage\Enum\Format;

// Initialize image manager with your driver
$imageManager = ImageManager::gd();  // or ImageManager::imagick()

// Create content
$content = new ImageContent(
    title: 'My Article Title',
    description: 'A brief description of the article'
);

// Generate image (uses default theme if not provided)
$generator = new Generator($imageManager);
$result = $generator->generate(
    data: $content,
    layout: new StandardLayout(),
    theme: null,  // optional - uses layout's default theme
    format: Format::Webp  // or Format::Png
);

// Use the result
$binary = $result->toString();        // Binary image data
$dataUri = $result->toBase64();       // Data URI for HTML
$stream = $result->toStream();        // Stream resource
$mimeType = $result->mimeType();      // 'image/webp' or 'image/png'
$width = $result->getWidth();         // Image width
$height = $result->getHeight();       // Image height
```

## Quick Start - Symfony Bundle

### Register the Bundle

```php
// config/bundles.php
return [
    // ...
    Void\OgImage\Symfony\OgImageBundle::class => ['all' => true],
];
```

### Use in Your Application

```php
use Void\OgImage\Generator;
use Void\OgImage\Model\ImageContent;
use Void\OgImage\Layout\StandardLayout;
use Void\OgImage\Enum\Format;

class ArticleService
{
    public function __construct(private Generator $generator) {}

    public function generateOgImage(string $title, string $description): string
    {
        $content = new ImageContent(
            title: $title,
            description: $description
        );

        $result = $this->generator->generate(
            data: $content,
            layout: new StandardLayout(),
            format: Format::Webp
        );

        // Save to filesystem or return binary
        return $result->toString();
    }
}
```

## Core Concepts

### ImageContent

The data model for your OG image:

```php
use Void\OgImage\Model\ImageContent;
use Void\OgImage\Model\Badge;

$content = new ImageContent(
    title: 'Article Title',                    // Required
    description: 'Article description',        // Optional
    badges: [                                  // Optional array of Badge objects
        new Badge('category', 'Technology'),
        new Badge('status', 'Published'),
    ],
    extras: []                                 // Optional - additional data for custom layouts
);
```

### Theme

Customize the appearance of your images:

```php
use Void\OgImage\Theme;
use Void\OgImage\Model\Background;
use Void\OgImage\Model\Font;

$theme = new Theme(
    primaryColor: '#6366f1',                          // Optional accent color
    background: new Background(
        color: '#0f172a',                             // Background color
        image: null,                                  // Optional background image path
        fit: Fit::Cover,                              // How to fit the image
        spacing: 20,                                  // Spacing for Repeat fit
        opacity: 0.8                                  // Opacity for image background
    ),
    textColor: '#ffffff',                             // Optional text color
    mutedColor: '#999999',                            // Optional muted/secondary text color
    titleFont: new Font(
        path: '/path/to/font.ttf',                    // Font file path
        size: 64,                                     // Font size in pixels
        color: '#000000'                              // Font color
    ),
    bodyFont: new Font(                               // For description text
        path: '/path/to/font.ttf',
        size: 32,
        color: '#333333'
    ),
    badgeFont: new Font(                              // For badge text
        path: '/path/to/font.ttf',
        size: 16,
        color: '#ffffff'
    ),
    padding: 40,                                      // Content padding
    logo: '/path/to/logo.png',                        // Optional logo/watermark
    logoScale: 0.5,                                   // Logo scale factor
    badges: null                                      // Optional default badge styles
);
```

### Background

Control how backgrounds are rendered:

```php
use Void\OgImage\Model\Background;
use Void\OgImage\Enum\Fit;

$background = new Background(
    color: '#ffffff',                    // Fallback/primary color
    image: '/path/to/image.png',        // Optional background image
    fit: Fit::Cover,                    // Fit mode: Cover, Contain, Center, Repeat, Stretch
    spacing: 10,                        // Spacing for Repeat mode
    opacity: 1.0                        // Opacity (0-1) for image
);
```

### Layouts

Built-in layouts for common patterns:

#### StandardLayout

The default layout with title, description, and badges:

```php
use Void\OgImage\Layout\StandardLayout;

$layout = new StandardLayout();
$result = $generator->generate($content, $layout);
```

### Image Formats

Generate WebP or PNG images:

```php
use Void\OgImage\Enum\Format;

// WebP (default, smaller file size, better compression)
$result = $generator->generate($content, $layout, $theme, Format::Webp);

// PNG (broader compatibility, larger file size)
$result = $generator->generate($content, $layout, $theme, Format::Png);
```

## Extending

### Custom Layouts

Create your own layout by extending `AbstractLayout`:

```php
use Intervention\Image\Interfaces\ImageManagerInterface;
use Void\OgImage\Canvas;
use Void\OgImage\Layout\AbstractLayout;
use Void\OgImage\Model\ImageContent;
use Void\OgImage\Theme;

class CustomLayout extends AbstractLayout
{
    public function defaultTheme(): Theme
    {
        return new Theme(
            primaryColor: '#ff0000',
            // ... other default theme properties
        );
    }

    public function build(
        ImageManagerInterface $imageManager,
        ImageContent $data,
        ?Theme $theme = null
    ): Canvas {
        $canvas = new Canvas(1200, 630, $imageManager);

        // Set background
        $canvas->setBackground($theme->background ?? new Background(color: '#ffffff'));

        // Add your custom rendering logic here
        // Use $canvas->add($box, $position) to add elements

        return $canvas;
    }
}
```

### Custom Fonts

Use different fonts for various text elements:

```php
use Void\OgImage\Model\Font;

$theme = new Theme(
    titleFont: new Font('/path/to/bold-font.ttf', 64, '#000000'),
    bodyFont: new Font('/path/to/regular-font.ttf', 32, '#333333'),
    badgeFont: new Font('/path/to/small-font.ttf', 16, '#ffffff')
);
```

### Custom Badges

Add badges to your content:

```php
use Void\OgImage\Model\Badge;
use Void\OgImage\Model\ImageContent;

$content = new ImageContent(
    title: 'Article Title',
    description: 'Description',
    badges: [
        new Badge('Technology', 'Python'),
        new Badge('Status', 'Published'),
        new Badge('Category', 'Tutorial'),
    ]
);
```

### Enums

The library includes helpful enums for configuration:

**Format** - Image output format:
```php
use Void\OgImage\Enum\Format;

Format::Webp  // Default
Format::Png
```

**Fit** - Background image fitting:
```php
use Void\OgImage\Enum\Fit;

Fit::Cover      // Crop to fill
Fit::Contain    // Scale to fit
Fit::Center     // Center without scaling
Fit::Repeat     // Tile the image
Fit::Stretch    // Stretch to fill
```

**TextAlignment** - Text alignment within boxes:
```php
use Void\OgImage\Enum\TextAlignment;

TextAlignment::Left
TextAlignment::Center
TextAlignment::Right
```

**Overflow** - Text overflow handling:
```php
use Void\OgImage\Enum\Overflow;

Overflow::Visible
Overflow::Hidden
Overflow::Ellipsis
```

**Placement** - Element positioning:
```php
use Void\OgImage\Enum\Placement;

Placement::TopLeft
Placement::Top
Placement::TopRight
Placement::Left
Placement::Center
Placement::Right
Placement::BottomLeft
Placement::Bottom
Placement::BottomRight
```

## Complete Example

```php
use Intervention\Image\ImageManager;
use Void\OgImage\Generator;
use Void\OgImage\Model\ImageContent;
use Void\OgImage\Model\Badge;
use Void\OgImage\Model\Font;
use Void\OgImage\Model\Background;
use Void\OgImage\Layout\StandardLayout;
use Void\OgImage\Theme;
use Void\OgImage\Enum\Format;

// Setup
$imageManager = ImageManager::gd();
$generator = new Generator($imageManager);

// Create content with badges
$content = new ImageContent(
    title: 'Getting Started with PHP 8.2',
    description: 'Learn the new features in PHP 8.2 and how to use them',
    badges: [
        new Badge('category', 'PHP'),
        new Badge('level', 'Intermediate'),
    ]
);

// Create a custom theme
$theme = new Theme(
    primaryColor: '#6366f1',
    background: new Background(color: '#0f172a'),
    textColor: '#ffffff',
    mutedColor: '#a0aec0',
    titleFont: new Font('/fonts/bold.ttf', 64, '#ffffff'),
    bodyFont: new Font('/fonts/regular.ttf', 32, '#a0aec0'),
    badgeFont: new Font('/fonts/medium.ttf', 16, '#6366f1'),
    padding: 50,
    logo: '/images/logo.png',
    logoScale: 0.6
);

// Generate the image
$result = $generator->generate(
    data: $content,
    layout: new StandardLayout(),
    theme: $theme,
    format: Format::Webp
);

// Output
echo "Generated OG image: {$result->getWidth()}x{$result->getHeight()}";
echo "MIME Type: {$result->mimeType()}";

// Save to file
file_put_contents('og-image.webp', $result->toString());

// Or get as data URI for HTML img tag
echo '<img src="' . $result->toBase64() . '" />';
```

## Testing

Run the test suite:

```bash
composer test
```

With coverage report:

```bash
composer test:coverage
```

## License

MIT License - see LICENSE file for details

## Author

Raphael Alogou - [raphalogou@gmail.com](mailto:raphalogou@gmail.com)
