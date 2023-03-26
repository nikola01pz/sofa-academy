<?php

declare(strict_types=1);

namespace SimpleFW\HTTP\Exception;

class NotFoundHttpException extends HttpException
{
    public function __construct(string $message = '', array $headers = [], ?\Throwable $previous = null)
    {
        parent::__construct(404, $message, $headers, $previous);
    }
}
