<?php

declare(strict_types=1);

namespace SimpleFW\HTTP;

final readonly class Cookie
{
    public function __construct(
        public string $name,
        public string $value = '',
        public int $expires = 0,
        public string $path = '',
        public string $domain = '',
        public bool $secure = false,
        public bool $httpOnly = false,
        public ?string $sameSite = null,
    ) {
    }

    public function getOptions(): array
    {
        return [
            'expires' => $this->expires,
            'path' => $this->path,
            'domain' => $this->domain,
            'secure' => $this->secure,
            'httponly' => $this->httpOnly,
            'samesite' => $this->sameSite,
        ];
    }
}
