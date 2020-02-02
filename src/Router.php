<?php

namespace Bgdnp\Foton\Http;

use Bgdnp\Foton\DI\Container;

class Router
{
    protected $container;
    protected $request;

    protected $namespace;

    protected $routes = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->request = $container->get(Request::class);
    }

    public function define(callable $defineFunction)
    {
        $builder = $this->container->get(RouteBuilder::class);
        $builder->setControllerNamespace($this->namespace);

        $defineFunction($builder);

        $this->routes = $builder->routes();
    }

    public function run()
    {
        $route = $this->resolveRequestRoute();

        $result = $route->execute($this->container);

        return $result;
    }

    public function setControllerNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    protected function resolveRequestRoute()
    {
        $requestRoute = $this->request->query('route', '/');
        $httpMethod = 'get';

        $resolver = $this->container->get(RouteResolver::class);

        return $resolver->resolve($requestRoute, $this->routes[$httpMethod]);
    }
}
