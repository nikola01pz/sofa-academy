<?php

declare(strict_types=1);

namespace SimpleFW\HTTP;

final class Request
{
    public readonly array $headers;
    public readonly string $method;
    public readonly string $scheme;
    public readonly string $host;
    public readonly string $requestUri;
    public readonly string $pathInfo;
    public readonly string $uri;
    public readonly \ArrayObject $attributes;
    private ?string $content = null;

    public function __construct(
        public readonly array $query = [],
        public readonly array $formData = [],
        public readonly array $cookies = [],
        public readonly array $files = [],
        public readonly array $server = [],
    ) {
        $headers = [];
        foreach ($server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[strtolower(str_replace('_', '-', substr($key, 5)))] = $value;
            }
        }
        $this->headers = $headers;

        $this->method = $this->server['REQUEST_METHOD'] ?? 'GET';
        $this->scheme = 'on' === strtolower($this->server['HTTPS'] ?? '') ? 'https' : 'http';
        $this->host = $this->headers['host'] ?? '';
        $this->requestUri = $this->server['REQUEST_URI'] ?? '';
        $this->pathInfo = (false !== $pos = strpos($this->requestUri, '?')) ? substr($this->requestUri, 0, $pos) : $this->requestUri;
        $this->uri = sprintf('%s://%s%s', $this->scheme, $this->host, $this->requestUri);
        $this->attributes = new \ArrayObject();
    }

    public static function createFromGlobals(): self
    {
        return new self($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function query(string $name, string|array $default = null): string|array|null
    {
        return $this->query[$name] ?? $default;
    }

    public function formData(string $name, string|array $default = null): string|array|null
    {
        return $this->formData[$name] ?? $default;
    }

    public function files(string $name, string|array $default = null): string|array|null
    {
        return $this->files[$name] ?? $default;
    }

    public function headers(string $name, ?string $default = null): ?string
    {
        return $this->headers[$name] ?? $default;
    }

    public function cookies(string $name, ?string $default = null): ?string
    {
        return $this->cookies[$name] ?? $default;
    }

    public function server(string $name, ?string $default = null): ?string
    {
        return $this->server[$name] ?? $default;
    }

    public function getContent(): string
    {
        if (null === $this->content) {
            $this->content = file_get_contents('php://input');
        }

        return $this->content;
    }
}
