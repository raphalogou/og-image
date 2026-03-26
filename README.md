# OG Image Bundle

A Symfony bundle for generating dynamic Open Graph (OG) images for social media sharing.

## Overview

The OG Image Bundle provides a flexible, extensible system to programmatically generate custom Open Graph images. Perfect for creating dynamic social media preview images with titles, descriptions, labels, and custom themes.

## Features

- 🎨 **Customizable Themes** - Define colors, backgrounds, and fonts with `mergeWith()` composition
- 📐 **Layout System** - Extend `Layout` for custom designs (StandardLayout included)
- 🖼️ **Image Composition** - Text boxes, image boxes, and rectangle shapes
- 🔄 **Format Support** - Render to PNG or WebP with configurable quality
- 🚀 **Stateless API** - Simple, functional approach with immediate rendering

## Requirements

- PHP 8.2+
- Symfony 7.4 or 8.0+ (optional - core bundle works standalone)
- Intervention Image 3.11+
- **Image Driver**: One of:
  - **GD** (bundled with PHP, recommended)
  - **Imagick** (optional, for better performance)

At least one image driver must be available. GD is included with most PHP installations by default.

## Installation

```bash
composer require void/og-image-bundle
```

Register the bundle in your Symfony configuration (if using Symfony):

```php
// config/bundles.php
return [
    // ...
    Void\OgImageBundle\OgImageBundle::class => ['all' => true],
];
```

Configure the bundle:

```yaml
# config/packages/og_image.yaml
og_image:
  driver: imagick        # 'imagick' (recommended) or 'gd' (default fallback)
  storage_dir: '%kernel.project_dir%/public/og-images'
```

**Driver Configuration:**
- `imagick` (recommended) - Best performance, requires `php-imagick` extension
- `gd` - Fallback option, bundled with PHP

## Quick Start

```php
use Void\OgImageBundle\Generator;
use Void\OgImageBundle\Model\ImageContent;
use Void\OgImageBundle\Model\Background;
use Void\OgImageBundle\Model\Font;
use Void\OgImageBundle\Model\Badge;
use Void\OgImageBundle\Theme;
use Void\OgImageBundle\Layout\StandardLayout;
use Void\OgImageBundle\Enum\Format;

// Create content
$content = new ImageContent(
    title: 'My Article Title',
    description: 'A brief description of the article',
    badges: [
        new Badge('category', 'Blog'),
        new Badge('date', '2026-03-25'),
    ]
);

// Define theme (optional - layout provides defaults)
$theme = new Theme(
    primaryColor: '#6366f1',
    background: new Background(color: '#0f172a'),
    titleFont: new Font('path/to/Inter-Bold.ttf', size: 64, color: '#ffffff'),
    bodyFont: new Font('path/to/Inter-Regular.ttf', size: 28, color: '#ffffff'),
    padding: 60
);

// Choose layout
$layout = new StandardLayout();

// Generate image
use Intervention\Image\ImageManager;

$imageManager = ImageManager::gd();  // or ImageManager::imagick()
$generator = new Generator($imageManager);
$result = $generator->generate(
    data: $content,
    layout: new StandardLayout(),
    theme: $theme,      // optional - uses layout defaults if null
    format: Format::Webp
);

If used in a Symfony application, the `Generator` can be autowired.

// Convert to various formats
$binary = $result->toString();        // Binary image data
$base64 = $result->toBase64();       // Data URI for HTML
$stream = $result->toStream();       // File pointer resource
$mimeType = $result->mimeType();    // 'image/webp'
$width = $result->getWidth();       // 1280
$height = $result->getHeight();     // 640
```

## Architecture

### Core Components

- **Generator** - Stateless service that orchestrates image generation
- **Layout** - Defines image dimensions and rendering logic (e.g., StandardLayout)
- **Theme** - Manages visual styling with `mergeWith()` support for composition
- **ImageContent** - Data model for image content (title, description, badges, extras)
- **Canvas** - Immediate-mode rendering surface for composing boxes
- **ImageResult** - Immutable result wrapper with conversion methods

### Models

- `ImageContent` - Container for title, description, badges, and extras
- `Background` - Background color or gradient configuration
- `Font` - Typography configuration (path, size, color)
- `Badge` - Label/value pairs for metadata display
- `Position` - Placement coordinates (supports pixels and percentages)
- `TextBox`, `ImageBox`, `RectangleBox` - Renderable elements

## Extending

### Custom Layouts

Extend the `Layout` abstract class to create custom layouts:

```php
use Intervention\Image\ImageManager;
use Void\OgImageBundle\Layout\AbstractLayout;
use Void\OgImageBundle\Model\ImageContent;
use Void\OgImageBundle\Theme;
use Void\OgImageBundle\Canvas;
use Void\OgImageBundle\Model\Background;
use Void\OgImageBundle\Model\Box\TextBox;
use Void\OgImageBundle\Model\Position;

class MyCustomLayout extends AbstractLayout
{
    public function build(\Intervention\Image\Interfaces\ImageManagerInterface $imageManager, ImageContent $content, ?Theme $theme = null): Canvas
    {
        $canvas = new Canvas(1200, 630, $imageManager);
        
        // Set background
        $canvas->setBackground($theme->background ?? new Background(color: '#ffffff'));
        
        // Add your custom rendering logic
        $title = new TextBox($content->title);
        $canvas->add($title, new Position(50, 50));
        
        return $canvas;
    }

    public function defaultTheme(): Theme
    {
        return new Theme(
            primaryColor: '#333333',
            textColor: '#000000',
            padding: 50
        );
    }
}
```

## License

MIT License - see LICENSE file for details

## Author

Raphael Alogou - [raphalogou@gmail.com](mailto:raphalogou@gmail.com)
