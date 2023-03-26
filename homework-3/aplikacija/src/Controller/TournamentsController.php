<?php

declare(strict_types=1);

namespace App\Controller;

use SimpleFW\HTTP\Response;
use SimpleFW\Templating\Templating;

final class TournamentsController
{
    public function __construct(
        private readonly Templating $templating,
    ) {
    }

    public function index(/* Request $request */): Response
    {
        $html = $this->templating->render('tournament/index.php', [
            'tournaments' => [
                'T1',
                'T2',
                'T3',
            ],
        ]);

        return new Response($html);
    }

    public function json(/* Request $request */): Response
    {
        $response = new Response(json_encode([
            'T1',
            'T2',
            'T3',
        ]));

        $response->addHeader('content-type', 'application/json');

        return $response;
    }
}
