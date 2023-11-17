<?php

declare(strict_types=1);

namespace SimpleFW\HTTP\Exception;

use SimpleFW\HTTP\Response;

class ControllerDoesNotReturnResponseException extends \LogicException
{
    public function __construct(string $controllerClass, string $action, ?\Throwable $previous = null)
    {
        $message = sprintf('The controller "%s::%s()" must return a "%s" object.', $controllerClass, $action, Response::class);

        parent::__construct($message, 0, $previous);
    }
}
