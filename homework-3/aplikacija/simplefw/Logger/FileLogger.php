<?php

declare(strict_types=1);

namespace SimpleFW\Logger;

use SimpleFW\Logger\Exception\IOException;

final readonly class FileLogger implements LoggerInterface
{
    public function __construct(
        private string $logFile,
    ) {
    }

    public function log(string $message, array $context = []): void
    {
        $dir = \dirname($this->logFile);

        if (!file_exists($dir)) {
            if (!@mkdir($dir, recursive: true)) {
                throw new IOException(sprintf('Failed to create the directory "%s".', $dir));
            }
        }

        foreach ($context as &$value) {
            if ($value instanceof \Throwable) {
                $value = $this->flattenException($value);
            }
        }

        $message = sprintf('[%s] "%s", %s', date(\DateTimeInterface::ATOM), $message, $context ? json_encode($context) : '');

        if (false === @file_put_contents($this->logFile, $message.\PHP_EOL, \FILE_APPEND)) {
            throw new IOException(sprintf('Failed to write to log file "%s".', $this->logFile));
        }
    }

    private function flattenException(\Throwable $e): array
    {
        return [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'trace' => $e->getTrace(),
            'previous' => ($previous = $e->getPrevious()) ? $this->flattenException($previous) : null,
        ];
    }
}
