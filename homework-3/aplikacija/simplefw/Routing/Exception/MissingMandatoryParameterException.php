<?php

declare(strict_types=1);

namespace SimpleFW\Routing\Exception;

class MissingMandatoryParameterException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct(
        public readonly string $route,
        public readonly string $parameter,
        ?\Throwable $previous = null,
    ) {
        $message = sprintf('The mandatory parameter "%s" is missing to generate a URL for route "%s".', $route, $parameter);

        parent::__construct($message, 0, $previous);
    }
}
