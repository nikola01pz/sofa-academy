<?php

declare(strict_types=1);

namespace SimpleFW\Routing\Exception;

class ResourceNotFoundException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(
        public readonly string $url,
        public readonly string $method,
        ?\Throwable $previous = null,
    ) {
        $message = sprintf('No route found for "%s %s".', $method, $url);

        parent::__construct($message, 0, $previous);
    }
}
