<?php

declare(strict_types=1);

namespace App;

use SimpleFW\HTTP\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }
}
