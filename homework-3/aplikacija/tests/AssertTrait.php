<?php

declare(strict_types=1);

namespace App\Tests;

trait AssertTrait
{
    public function assert($expected, $actual): void
    {
        echo sprintf('Asserting that "%s" is equal to "%s". ', json_encode($expected), json_encode($actual));
        if ($expected !== $actual) {
            echo "\033[31mFAILED\033[0m", \PHP_EOL;
            throw new AssertionException();
        } else {
            echo "\033[32mSUCCESS\033[0m", \PHP_EOL;
        }
    }

    public function assertException(string $expectedException, callable $closure): void
    {
        echo sprintf('Asserting that an exception of type "%s" was thrown. ', $expectedException);

        try {
            $closure();
            echo "\033[31mFAILED, no exception was thrown\033[0m", \PHP_EOL;
            throw new AssertionException();
        } catch (\Throwable $e) {
            $this->assert($expectedException, $e::class);
        }
    }
}
