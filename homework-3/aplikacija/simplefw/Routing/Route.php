<?php

declare(strict_types=1);

namespace SimpleFW\Routing;

final readonly class Route
{
    public function __construct(
        public string $name,
        public string $path,
        public string|array $controller,
        public ?string $host = null,
        public string|null $method = null,
        public array $requirements = [],
    ) {
    }
}
