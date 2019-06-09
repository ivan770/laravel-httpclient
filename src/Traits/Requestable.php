<?php

namespace Ivan770\HttpClient\Traits;

use Ivan770\HttpClient\Response;

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
        $this->applyRequestOptions($arguments[1] ?? []);
        $request = $this->returnAndResetBuilderState();
        $response = $this->client->request(strtoupper($name), $arguments[0], $request);
        return new Response($response);
    }
}