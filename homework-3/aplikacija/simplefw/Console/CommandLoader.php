<?php

declare(strict_types=1);

namespace SimpleFW\Console;

use SimpleFW\Console\Exception\CommandNotFoundException;
use SimpleFW\DependencyInjection\Container;

final readonly class CommandLoader
{
    public function __construct(
        private Container $container,
        private array $commands,
    ) {
    }

    public function get(string $name): CommandInterface
    {
        if (!isset($this->commands[$name])) {
            throw new CommandNotFoundException($name);
        }

        $id = $this->commands[$name];

        return $this->container->has($id) ? $this->container->get($id) : new $id();
    }

    public function allNames(): array
    {
        return array_keys($this->commands);
    }
}
