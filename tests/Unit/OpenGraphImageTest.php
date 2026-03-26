<?php

namespace Void\OgImage\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Void\OgImage\OpenGraphImage;

class OpenGraphImageTest extends TestCase
{
    private function createMockEncodedImage(string $content = 'test-image-data'): \Intervention\Image\Interfaces\EncodedImageInterface
    {
        $mock = $this->createMock(\Intervention\Image\Interfaces\EncodedImageInterface::class);

        $mock->method('toString')
            ->willReturn($content);

        $mock->method('toDataUri')
            ->willReturn('data:image/png;base64,'.base64_encode($content));

        $mock->method('toFilePointer')
            ->willReturn(fopen('php://memory', 'r+'));

        return $mock;
    }

    public function testToStringDelegates(): void
    {
        $encoded = $this->createMockEncodedImage('binary-image-content');
        $result = new OpenGraphImage($encoded, 640, 480, 'image/png');

        $this->assertSame('binary-image-content', $result->toString());
    }

    public function testToBase64DelegatesAndIncludesMimeType(): void
    {
        $encoded = $this->createMockEncodedImage('test-data');
        $result = new OpenGraphImage($encoded, 1000, 500, 'image/png');

        $base64 = $result->toBase64();

        $this->assertStringStartsWith('data:image/png;base64,', $base64);
        $this->assertStringContainsString(base64_encode('test-data'), $base64);
    }

    public function testToStreamReturnsResource(): void
    {
        $encoded = $this->createMockEncodedImage();
        $result = new OpenGraphImage($encoded, 1280, 640, 'image/png');

        $stream = $result->toStream();

        $this->assertIsResource($stream); // @phpstan-ignore method.alreadyNarrowedType
    }
}
