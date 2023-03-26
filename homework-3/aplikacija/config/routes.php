<?php

declare(strict_types=1);

use App\Controller\HomeController;
use App\Controller\TournamentsController;
use SimpleFW\Routing\Route;
use SimpleFW\Routing\Router;

return static function (Router $router) {
    $router
        ->addRoute(new Route('home', '/', HomeController::class))
        ->addRoute(new Route('tournaments', '/tournaments', [TournamentsController::class, 'index']))
        ->addRoute(new Route('tournaments-json', '/tournaments-json', [TournamentsController::class, 'json']))
        // ->addRoute(new Route('tournaments', '/tournaments', ['tournaments.controller', 'index']))
        // ->addRoute(new Route('tournaments-json', '/tournaments-json', ['tournaments.controller', 'json']))
    ;
};
