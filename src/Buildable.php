<?php

namespace Ivan770\HttpClient;

trait Buildable
{
    protected $request = [];

    protected function applyRequestOptions($options)
    {
        $this->request = array_merge($this->request, $options);
    }

    protected function resetBuilderState()
    {
        $this->request = [];
    }

    protected function returnAndResetBuilderState()
    {
        $request = $this->request;
        $this->resetBuilderState();
        return $request;
    }

    public function auth($type, $credentials)
    {
        $this->applyRequestOptions([$type => $credentials]);
        return $this;
    }

    public function authBasic($credentials)
    {
        $this->applyRequestOptions(["auth_basic" => $credentials]);
        return $this;
    }

    public function authBearer($credentials)
    {
        $this->applyRequestOptions(["auth_bearer" => $credentials]);
        return $this;
    }

    public function headers($headers)
    {
        $this->applyRequestOptions(["headers" => $headers]);
        return $this;
    }

    public function body($body)
    {
        $this->applyRequestOptions(["body" => $body]);
        return $this;
    }

    public function json($json)
    {
        $this->applyRequestOptions(["json" => $json]);
        return $this;
    }

    public function query($query)
    {
        $this->applyRequestOptions(["query" => $query]);
        return $this;
    }
}