<?php

namespace Ivan770\HttpClient\Traits;

use Ivan770\HttpClient\Response\Response;

trait Requestable
{
    protected $methods = [
        'get',
        'head',
        'post',
        'put',
        'delete',
        'connect',
        'options',
        'trace',
        'patch',
    ];

    protected function isRequestMethod($name)
    {
        return in_array($name, $this->methods);
    }

    protected function callRequestMethod($name, $arguments)
    {
        $this->builder->applyRequestOptions($arguments[1] ?? []);
        $request = $this->getRequest();

        $response = $this->client->request(strtoupper($name), $arguments[0], $request);
        return new Response($response);
    }

    protected function getRequest()
    {
        $request = $this->builder->getRequest();
        $this->builder->resetRequest();

        return $request;
    }
}