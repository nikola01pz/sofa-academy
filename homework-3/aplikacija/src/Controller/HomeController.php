<?php

declare(strict_types=1);

namespace App\Controller;

use SimpleFW\HTTP\Request;
use SimpleFW\HTTP\Response;
use SimpleFW\Templating\Templating;

final class HomeController
{
    public function __construct(
        private readonly Templating $templating,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->templating->render('home/index.php'));
    }
}
