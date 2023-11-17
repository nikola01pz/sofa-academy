<?php

declare(strict_types=1);

namespace SimpleFW\DependencyInjection\Exception;

class ServiceNotFoundException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct(
        public readonly string $id,
        ?\Throwable $previous = null,
    ) {
        $message = sprintf('You have requested a non-existent service "%s".', $id);

        parent::__construct($message, 0, $previous);
    }
}
