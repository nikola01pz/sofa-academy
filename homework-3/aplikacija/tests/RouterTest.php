<?php

declare(strict_types=1);

namespace App\Tests;

use SimpleFW\HTTP\Request;
use SimpleFW\Routing\Exception\ResourceNotFoundException;
use SimpleFW\Routing\Route;
use SimpleFW\Routing\Router;

final class RouterTest
{
    use AssertTrait;

    public function run(): void
    {
        $router = new Router();

        $router
            ->addRoute(new Route('home', '/', \stdClass::class))
            ->addRoute(new Route('tournaments', '/tournaments', [\stdClass::class, 'index']))
            ->addRoute(new Route('tournaments-json', '/tournaments-json', [\stdClass::class, 'json']))
            ->addRoute(new Route('api', '/api', \stdClass::class, 'other-host.com'))
            ->addRoute(new Route('api-post', '/api/post', \stdClass::class, 'other-host.com', 'POST'))
            ->addRoute(new Route('with-parameter', '/api/sport/{slug}', \stdClass::class))
            ->addRoute(new Route('with-parameters', '/api/sport/{slug}/event/{id}', \stdClass::class))
            ->addRoute(new Route('with-requirements', '/api/event/{id}', \stdClass::class, requirements: ['id' => '\d+']))
        ;

        $this->assert(
            'home',
            $router->match(new Request(server: ['REQUEST_URI' => '/', 'HTTP_HOST' => 'localhost']))->name,
        );

        $this->assert(
            'tournaments',
            $router->match(new Request(server: ['REQUEST_URI' => '/tournaments', 'HTTP_HOST' => 'localhost']))->name,
        );

        $this->assert(
            'tournaments-json',
            $router->match(new Request(server: ['REQUEST_URI' => '/tournaments-json', 'HTTP_HOST' => 'localhost']))->name,
        );

        $this->assertException(
            ResourceNotFoundException::class,
            fn () => $router->match(new Request(server: ['REQUEST_URI' => '/api', 'HTTP_HOST' => 'localhost']))->name,
        );

        $this->assert(
            'api',
            $router->match(new Request(server: ['REQUEST_URI' => '/api', 'HTTP_HOST' => 'other-host.com']))->name,
        );

        $this->assertException(
            ResourceNotFoundException::class,
            fn () => $router->match(new Request(server: ['REQUEST_URI' => '/api-post', 'HTTP_HOST' => 'other-host.com']))->name,
        );

        $this->assert(
            'api-post',
            $router->match(new Request(server: ['REQUEST_URI' => '/api/post', 'HTTP_HOST' => 'other-host.com', 'REQUEST_METHOD' => 'POST']))->name,
        );

        $request = new Request(server: ['REQUEST_URI' => '/api/sport/some-sport', 'HTTP_HOST' => 'other-host.com']);
        $this->assert('with-parameter', $router->match($request)->name);
        $this->assert('with-parameter', $request->attributes['_route_name']);
        $this->assert('some-sport', $request->attributes['_route_params']['slug']);

        $request = new Request(server: ['REQUEST_URI' => '/api/sport/some-sport/event/1', 'HTTP_HOST' => 'other-host.com']);
        $this->assert('with-parameters', $router->match($request)->name);
        $this->assert('with-parameters', $request->attributes['_route_name']);
        $this->assert('some-sport', $request->attributes['_route_params']['slug']);
        $this->assert('1', $request->attributes['_route_params']['id']);

        $this->assertException(
            ResourceNotFoundException::class,
            fn () => $router->match(new Request(server: ['REQUEST_URI' => '/api/event/some-event', 'HTTP_HOST' => 'other-host.com']))->name,
        );

        $request = new Request(server: ['REQUEST_URI' => '/api/event/33', 'HTTP_HOST' => 'other-host.com']);
        $this->assert('with-requirements', $router->match($request)->name);
        $this->assert('with-requirements', $request->attributes['_route_name']);
        $this->assert('33', $request->attributes['_route_params']['id']);
    }
}
