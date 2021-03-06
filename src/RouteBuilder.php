<?php

namespace Bgdnp\FotonRouter;

class RouteBuilder
{
    protected $namespace;
    protected $routes = [
        'get' => [],
        'post' => [],
        'put' => [],
        'patch' => [],
        'delete' => [],
    ];

    public function routes(): array
    {
        return $this->routes;
    }

    public function get(string $path, ...$args)
    {
        $this->createRoute('get', $path, $args);

        return $this;
    }

    public function post(string $path, ...$args)
    {
        $this->createRoute('post', $path, $args);

        return $this;
    }

    public function put(string $path, ...$args)
    {
        $this->createRoute('put', $path, $args);

        return $this;
    }

    public function patch(string $path, ...$args)
    {
        $this->createRoute('patch', $path, $args);

        return $this;
    }

    public function delete(string $path, ...$args)
    {
        $this->createRoute('delete', $path, $args);

        return $this;
    }

    public function setControllerNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    protected function createRoute(string $httpMethod, string $path, array $args)
    {
        $route = new Route($httpMethod, $path, $args, $this->namespace);

        $this->routes[$httpMethod][$route->path()] = $route;
    }
}
