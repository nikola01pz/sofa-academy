<?php

declare(strict_types=1);

namespace SimpleFW\Routing;

use SimpleFW\HTTP\Request;

final class Router
{
    /** @var Route[] */
    private array $routes = [];

    public function addRoute(Route $route): self
    {
        // @TODO: Zadatak 1

        $regex = '^'. preg_quote($route->path, '/') . '$';
        $this->routes[$regex] = $route;

        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function match(Request $request): Route
    {
        // @TODO: Zadatak 1
        foreach ($this->routes as $regex => $route) {
            if (preg_match('/' . $regex . '/', $request->requestUri)) {
                return $route;
            }
        }
            // $request->attributes['_route_name'] = $route->name;
            // $request->attributes['_route_params'] = $matches;
        throw new Exception\ResourceNotFoundException($request->method, $request->requestUri);
    }
}
