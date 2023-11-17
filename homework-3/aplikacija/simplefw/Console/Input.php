<?php

declare(strict_types=1);

namespace SimpleFW\Console;

use SimpleFW\Console\Exception\InvalidArgumentException;

final class Input
{
    private readonly array $argv;
    private array $arguments = [];
    private array $options = [];

    public function __construct(array $argv)
    {
        array_shift($argv);
        $this->argv = $argv;

        $this->parse();
    }

    public function getArgument(int $key): string
    {
        return $this->arguments[$key] ?? throw new InvalidArgumentException(sprintf('An argument with the key "%d" does not exist.', $key));
    }

    public function hasArgument(int $key): bool
    {
        return isset($this->arguments[$key]);
    }

    public function getOption(string $name): string
    {
        return $this->options[$name] ?? throw new InvalidArgumentException(sprintf('The option "%s" does not exist.', $name));
    }

    public function hasOption(string $name): bool
    {
        return isset($this->options[$name]);
    }

    private function parse(): void
    {
        $tokens = $this->argv;

        $parseOptions = true;
        while (null !== $token = array_shift($tokens)) {
            if ($parseOptions && '--' == $token) {
                $parseOptions = false;
            } elseif ($parseOptions && str_starts_with($token, '--')) {
                $this->options[substr($token, 2)] = array_shift($tokens);
            } elseif ($parseOptions && '-' === $token[0] && '-' !== $token) {
                $this->options[substr($token, 1)] = array_shift($tokens);
            } else {
                $this->arguments[] = $token;
            }
        }
    }
}
