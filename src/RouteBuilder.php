<?php

namespace Bgdnp\Foton\Http;

class RouteBuilder
{
    protected $routes = [
        'get' => []
    ];

    public function routes(): array
    {
        return $this->routes;
    }

    public function get(string $path, ...$args)
    {
        $this->createRoute('get', $path, $args);
    }

    protected function createRoute(string $httpMethod, string $path, array $args)
    {
        $route = new Route($httpMethod, $path, $args);

        $this->routes[$httpMethod][$route->path] = $route;
    }
}
