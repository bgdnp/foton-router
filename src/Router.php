<?php

namespace Bgdnp\FotonRouter;

use Bgdnp\FotonDI\Container;
use Bgdnp\FotonHttp\Request;
use Bgdnp\FotonHttp\Response;

class Router
{
    protected $container;
    protected $request;
    protected $response;

    protected $namespace;

    protected $routes = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->request = $container->get(Request::class);
        $this->response = $container->get(Response::class);
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

        return $this->response->send($result);
    }

    public function setControllerNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    protected function resolveRequestRoute()
    {
        $requestRoute = $this->request->query('route', '/');
        $httpMethod = $this->request->httpMethod();

        $resolver = $this->container->get(RouteResolver::class);

        return $resolver->resolve($requestRoute, $this->routes[$httpMethod]);
    }
}
