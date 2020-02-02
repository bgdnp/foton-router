<?php

namespace Bgdnp\Foton\Http;

use Bgdnp\Foton\DI\Container;

class Route
{
    protected $httpMethod;
    protected $path;
    protected $controller;
    protected $method;
    protected $parameters;

    public function __construct(string $httpMethod, string $path, array $args, string $namespace)
    {
        $this->httpMethod = $httpMethod;
        $this->setPath($path);
        $this->setController($args, $namespace);
        $this->setMethod($args);
        $this->setParameters($path);
    }

    public function path()
    {
        return $this->path;
    }

    public function setParameter($key, $value)
    {
        $key = str_replace(['[', ']'], '', $key);

        if (array_key_exists($key, $this->parameters)) {
            $this->parameters[$key] = $value;
        }
    }

    public function execute(Container $container)
    {
        return $container->parameters($this->parameters)->invoke($this->controller, $this->method);
    }

    protected function setPath(string $path)
    {
        $this->path = '/' . trim($path, ' /');
    }

    protected function setController(array $args, string $namespace)
    {
        if (empty($args[1])) {
            $args = explode('@', $args[0]);
        }

        $namespace = '\\' . trim($namespace, '\\');
        $namespace = str_replace('/', '\\', $namespace);
        $controller = '\\' . trim($args[0], '\\');
        $controller = str_replace('/', '\\', $controller);

        $this->controller = $namespace . $controller;
    }

    protected function setMethod(array $args)
    {
        if (empty($args[1])) {
            $args = explode('@', $args[0]);
        }

        $this->method = $args[1];
    }

    protected function setParameters(string $path)
    {
        preg_match_all('/\[([^\]]+)\]/', $path, $matches);

        $this->parameters = array_flip($matches[1]);
    }
}
