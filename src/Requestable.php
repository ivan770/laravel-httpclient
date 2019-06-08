<?php

namespace Ivan770\HttpClient;

trait Requestable
{
    protected $methods = [
        "get",
        "head",
        "post",
        "put",
        "delete",
    ];

    protected function isRequestMethod($name)
    {
        return in_array($name, $this->methods);
    }

    protected function callRequestMethod($name, $arguments)
    {
        $response = $this->client->request(strtoupper($name), $arguments[0], $arguments[1] ?? []);
        return new Response($response);
    }
}