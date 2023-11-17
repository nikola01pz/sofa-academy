<?php

declare(strict_types=1);

namespace SimpleFW\Console;

interface CommandInterface
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    public function execute(Input $input, Output $output): int;
}
