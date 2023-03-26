<?php

declare(strict_types=1);

namespace SimpleFW\DependencyInjection\Exception;

class ParameterNotFoundException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct(
        public readonly string $parameter,
        ?\Throwable $previous = null,
    ) {
        $message = sprintf('You have requested a non-existent parameter "%s".', $parameter);

        parent::__construct($message, 0, $previous);
    }
}
