<?php

namespace Bgdnp\Foton\Http;

class RouteBuilder
{
    protected $namespace;
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

    public function setControllerNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    protected function createRoute(string $httpMethod, string $path, array $args)
    {
        $route = new Route($httpMethod, $path, $args, $this->namespace);

        $this->routes[$httpMethod][$route->path] = $route;
    }
}
