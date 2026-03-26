<?php

namespace Void\OgImage\Exception;

class ImageStorageException extends \RuntimeException
{
    public function __construct(string $message = 'Image storage operation failed', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
