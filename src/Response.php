<?php

namespace Bgdnp\Foton\Http;

class Response
{
    public function send($data)
    {
        echo json_encode($data);
    }
}
