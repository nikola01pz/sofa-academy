<?php

declare(strict_types=1);

namespace SimpleFW\Console;

final readonly class ListCommand implements CommandInterface
{
    public function __construct(private CommandLoader $commandLoader)
    {
    }

    public function execute(Input $input, Output $output): int
    {
        $output->writeln('List of available commands:');

        foreach ($this->commandLoader->allNames() as $name) {
            $output->writeln(' - '.$name);
        }

        return self::SUCCESS;
    }
}
