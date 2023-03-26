<?php

declare(strict_types=1);

namespace SimpleFW\Console\Exception;

class CommandNotFoundException extends InvalidArgumentException
{
    public function __construct(string $name, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('The command "%s" does not exist.', $name), 0, $previous);
    }
}
