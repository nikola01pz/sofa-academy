<?php

declare(strict_types=1);

namespace App\Tests;

use SimpleFW\HTTP\Kernel;
use SimpleFW\HTTP\Request;

final class KernelTest
{
    use AssertTrait;

    public function run(): void
    {
        require __DIR__.'/StubController.php';

        $kernel = new class() extends Kernel {
            public function getProjectDir(): string
            {
                return '';
            }
        };

        $reflection = new \ReflectionMethod($kernel, 'resolveArguments');

        $request = new Request();
        $request->attributes['_route_params'] = [
            'id' => '33',
            'slug' => 'some-sport',
        ];

        $this->assert(
            ['request' => $request, 'slug' => 'some-sport'],
            $reflection->invoke($kernel, StubController::class, 'actionOne', $request),
        );

        $this->assert(
            ['id' => 33, 'request' => $request],
            $reflection->invoke($kernel, StubController::class, 'actionTwo', $request),
        );

        $this->assert(
            ['id' => 33, 'slug' => 'some-sport'],
            $reflection->invoke($kernel, StubController::class, 'actionThree', $request),
        );

        $this->assert(
            ['name' => null],
            $reflection->invoke($kernel, StubController::class, 'actionFour', $request),
        );
    }
}
