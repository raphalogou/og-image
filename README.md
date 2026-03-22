# OG Image Bundle

A Symfony bundle for generating dynamic Open Graph (OG) images for social media sharing.

## Overview

The OG Image Bundle provides a flexible, extensible system to programmatically generate custom Open Graph images. Perfect for creating dynamic social media preview images with titles, descriptions, labels, and custom themes.

## Features

- 🎨 **Customizable Themes** - Define colors, backgrounds, and styling
- 📐 **Layout System** - Pre-built layouts with extensibility for custom designs
- 🖼️ **Image Composition** - Add text boxes, rectangles, images, and watermarks
- 💾 **Storage Integration** - Built-in filesystem storage with extensible interface
- 🚀 **Symfony Integration** - Seamless bundle integration with Symfony 7.4+/8.0+

## Installation

```bash
composer require void/og-image-bundle
```

Register the bundle in your Symfony configuration:

```php
// config/bundles.php
return [
    // ...
    Void\OgImageBundle\OgImageBundle::class => ['all' => true],
];
```

Configure the storage directory in your Symfony config:

```yaml
# config/packages/og_image.yaml
og_image:
  storage_dir: '%kernel.project_dir%/public/og-images'
```

## Quick Start

```php
use Void\OgImageBundle\Generator;
use Void\OgImageBundle\Model\Content;
use Void\OgImageBundle\Theme\Theme;
use Void\OgImageBundle\Layout\StackedLayout;

// Create content
$content = new Content(
    title: 'My Article Title',
    description: 'A brief description of the article',
    label: 'Blog'
);

// Define theme
$theme = new Theme(
    background: new Background(color: '#ffffff'),
    // Add text fonts and styling
);

// Choose layout
$layout = new StackedLayout();

// Generate image
$image = $this->generator->generate($content, $theme, $layout);

// Save to file
$image->save('path/to/save.webp');
```

## Architecture

### Core Components

- **Generator** - Main service that orchestrates image generation
- **Layout** - Defines image dimensions and rendering logic
- **Theme** - Manages visual styling and appearance
- **Content** - Data model for image content (title, description, label)
- **Storage** - Handles image persistence and file management

### Models

- `Content` - Container for title, description, and label
- `Dimensions` - Image width and height
- `Background` - Background color and properties
- `TextBox`, `ImageBox`, `RectangleBox` - Renderable elements
- `TextFont` - Typography configuration
- `Watermark` - Watermark overlay support

## Extending

### Custom Layouts

Implement `LayoutInterface` to create custom layouts:

```php
class MyCustomLayout implements LayoutInterface
{
    public function getDimensions(): Dimensions
    {
        return new Dimensions(1200, 630);
    }

    public function render(ImageInterface $image, Content $content, Theme $theme): void
    {
        // Your rendering logic
    }
}
```

### Custom Storage

Implement `StorageInterface` to use alternative storage backends:

```php
class S3Storage implements StorageInterface
{
    // Your S3 storage implementation
}
```

## Requirements

- PHP 8.2+
- Symfony 7.4 or 8.0+
- Intervention Image 3.11+

## License

MIT License - see LICENSE file for details

## Author

Raphael Alogou - [raphalogou@gmail.com](mailto:raphalogou@gmail.com)