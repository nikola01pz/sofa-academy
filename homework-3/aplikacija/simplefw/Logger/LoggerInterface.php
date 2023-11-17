<?php

declare(strict_types=1);

namespace SimpleFW\Logger;

interface LoggerInterface
{
    public function log(string $message, array $context = []): void;
}
