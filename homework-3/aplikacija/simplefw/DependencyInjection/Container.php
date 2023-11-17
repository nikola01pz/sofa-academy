<?php

declare(strict_types=1);

namespace SimpleFW\DependencyInjection;

use SimpleFW\DependencyInjection\Exception\ParameterNotFoundException;
use SimpleFW\DependencyInjection\Exception\ServiceNotFoundException;

final class Container
{
    private array $parameters = [];
    /** @var callable[] */
    private array $factories = [];
    /** @var object[] */
    private array $services = [];

    public function getParameter(string $name): mixed
    {
        return $this->parameters[$name] ?? throw new ParameterNotFoundException($name);
    }

    public function hasParameter(string $name): bool
    {
        return \array_key_exists($name, $this->parameters);
    }

    public function setParameter(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    public function addFactory(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function getFactory(string $id): callable
    {
        return $this->factories[$id] ?? throw new ServiceNotFoundException($id);
    }

    public function hasFactory(string $id): bool
    {
        return isset($this->factories[$id]);
    }

    /**
     * @template T
     *
     * @param class-string<T> $id
     *
     * @return T
     */
    public function get(string $id): object
    {
        if (isset($this->services[$id])) {
            return $this->services[$id];
        }

        $factory = $this->factories[$id] ?? throw new ServiceNotFoundException($id);

        return $this->services[$id] = $factory($this);
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]) || $this->hasFactory($id);
    }

    public function set(string $id, object $service): self
    {
        $this->services[$id] = $service;

        return $this;
    }
}
