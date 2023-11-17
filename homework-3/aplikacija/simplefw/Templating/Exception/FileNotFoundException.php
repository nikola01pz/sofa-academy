<?php

declare(strict_types=1);

namespace SimpleFW\Templating\Exception;

class FileNotFoundException extends \RuntimeException
{
    public function __construct(
        public readonly string $path,
        ?\Throwable $previous = null,
    ) {
        $message = sprintf('File "%s" could not be found.', $path);

        parent::__construct($message, 0, $previous);
    }
}
