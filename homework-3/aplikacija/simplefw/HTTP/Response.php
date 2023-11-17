<?php

declare(strict_types=1);

namespace SimpleFW\HTTP;

final class Response
{
    /**
     * @param Cookie[] $cookies
     */
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = [],
        private array $cookies = [],
    ) {
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addCookie(Cookie $cookie): self
    {
        $this->cookies[$cookie->name] = $cookie;

        return $this;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function send(): void
    {
        // Headers have already been sent
        if (headers_sent()) {
            return;
        }

        foreach ($this->headers as $name => $value) {
            header($name.': '.$value);
        }

        foreach ($this->cookies as $cookie) {
            setcookie($cookie->name, $cookie->value, $cookie->getOptions());
        }

        http_response_code($this->statusCode);

        echo $this->content;
    }
}
