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

        $defineFunction($builder);

        $this->routes = $builder->routes();
    }

    public function run()
    {
        var_dump($this->routes);
    }

    public function setControllerNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }
}
