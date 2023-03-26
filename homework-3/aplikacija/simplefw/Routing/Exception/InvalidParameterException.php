<?php

declare(strict_types=1);

namespace SimpleFW\Routing\Exception;

class InvalidParameterException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct(
        public readonly string $route,
        public readonly string $parameter,
        public readonly string $pattern,
        public readonly string $value,
        ?\Throwable $previous = null,
    ) {
        $message = sprintf('Parameter "%s" for route "%s" must match "%s", "%s" given.', $route, $parameter, $pattern, $value);

        parent::__construct($message, 0, $previous);
    }
}
