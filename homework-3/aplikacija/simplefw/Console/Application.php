<?php

declare(strict_types=1);

namespace SimpleFW\Console;

use SimpleFW\HTTP\Kernel;

final readonly class Application
{
    public function __construct(private Kernel $kernel)
    {
    }

    public function run(Input $input): void
    {
        $this->kernel->boot();

        $commandLoader = $this->kernel->getContainer()->get(CommandLoader::class);

        $output = new Output();

        /** @var CommandInterface $command */
        $command = $commandLoader->get(
            $input->hasArgument(0) ? $input->getArgument(0) : 'list',
        );

        try {
            $exitCode = $command->execute($input, $output);
        } catch (\Throwable $e) {
            $output->writeln($e->getMessage());
            $output->writeln($e->getTraceAsString());
            $exitCode = 1;
        }

        exit($exitCode);
    }
}
