<?php

declare(strict_types=1);

namespace App\Tests;

use SimpleFW\HTTP\Request;

final class StubController
{
    public function actionOne(Request $request, string $slug)
    {
    }

    public function actionTwo(int $id, Request $request)
    {
    }

    public function actionThree(int $id, string $slug)
    {
    }

    public function actionFour(?string $name)
    {
    }
}
