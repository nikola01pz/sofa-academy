<?php

declare(strict_types=1);

namespace SimpleFW\Routing\Exception;

class RouteNotFoundException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct(
        public readonly string $route,
        public readonly array $params = [],
        ?\Throwable $previous = null,
    ) {
        $message = sprintf('You have requested a non-existent route "%s".', $route);

        parent::__construct($message, 0, $previous);
    }
}
