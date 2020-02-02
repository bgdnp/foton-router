<?php

namespace Bgdnp\Foton\Http;

class Route
{
    public $httpMethod;
    public $path;
    public $controller;
    public $method;
    public $parameters;

    public function __construct(string $httpMethod, string $path, array $args, string $namespace)
    {
        $this->httpMethod = $httpMethod;
        $this->setPath($path);
        $this->setController($args, $namespace);
        $this->setMethod($args);
        $this->setParameters($path);
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

    public function setParameter($key, $value)
    {
        if (array_key_exists($key, $this->parameters)) {
            $this->parameters[$key] = $value;
        }
    }
}
