<?php

namespace Bgdnp\Foton\Http;

class Request
{
    protected $get;
    protected $post;
    protected $server = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->setServerData();
    }

    public function query(?string $key = null, ?string $default = null)
    {
        if ($key) {
            return !empty($this->get[$key]) ? $this->get[$key] : $default;
        }

        return $this->get;
    }

    public function form(?string $key = null, ?string $default = null)
    {
        if ($key) {
            return !empty($this->post[$key]) ? $this->post[$key] : $default;
        }

        return $this->post;
    }

    public function input(string $key, ?string $default = null)
    {
        if ($this->query($key)) {
            return $this->query($key);
        }

        if ($this->httpMethod() !== 'get') {
            return $default;
        }

        if ($this->form($key)) {
            return $this->form($key);
        }

        $body = json_decode($this->getRawBody(), true);

        if ($body[$key]) {
            return $body[$key];
        }

        return $default;
    }

    public function body()
    {
        $raw = $this->getRawBody();

        return json_decode($raw);
    }

    public function httpMethod()
    {
        return strtolower($this->requestMethod);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->server)) {
            return $this->server[$name];
        }

        return $this->input($name, null);
    }

    protected function setServerData()
    {
        foreach ($_SERVER as $key => $value) {
            $key = strtolower($key);
            $key = preg_replace_callback('/_([a-z]{1})/', function ($matches) {
                return strtoupper(($matches[1]));
            }, $key);
            $this->server[$key] = $value;
        }
    }

    protected function getRawBody()
    {
        if ($this->httpMethod() === 'post') {
            return file_get_contents('php://input');
        }

        return null;
    }
}
