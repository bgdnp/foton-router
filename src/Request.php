<?php

namespace Bgdnp\Foton\Http;

class Request
{
    protected $query;

    public function __construct()
    {
        $this->query = $_GET;
    }

    public function query(?string $key = null, ?string $default = null)
    {
        if ($key) {
            return !empty($this->query[$key]) ? $this->query[$key] : $default;
        }

        return $this->query;
    }
}
