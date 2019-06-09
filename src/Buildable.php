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

    /**
     * Add authentication to request
     *
     * @param string $type Authentication type
     * @param array|string $credentials Authentication credentials
     * @return $this
     */
    public function auth($type, $credentials)
    {
        $this->applyRequestOptions([$type => $credentials]);
        return $this;
    }

    /**
     * Add HTTP basic auth to request
     *
     * @param array|string $credentials "Authentication credentials"
     * @return $this
     */
    public function authBasic($credentials)
    {
        $this->applyRequestOptions(["auth_basic" => $credentials]);
        return $this;
    }

    /**
     * Add Bearer token to request
     *
     * @param string $credentials "Bearer token"
     * @return $this
     */
    public function authBearer($credentials)
    {
        $this->applyRequestOptions(["auth_bearer" => $credentials]);
        return $this;
    }

    /**
     * Add headers to request
     *
     * @param array $headers Headers
     * @return $this
     */
    public function headers($headers)
    {
        $this->applyRequestOptions(["headers" => $headers]);
        return $this;
    }

    /**
     * Add body to request
     *
     * @param array|string|resource|\Traversable|\Closure $body Request body
     * @return $this
     */
    public function body($body)
    {
        $this->applyRequestOptions(["body" => $body]);
        return $this;
    }

    /**
     * Add JSON to request
     *
     * @param array|\JsonSerializable $json JSON-compatible value
     * @return $this
     */
    public function json($json)
    {
        $this->applyRequestOptions(["json" => $json]);
        return $this;
    }

    /**
     * Add query string values to request
     *
     * @param array $query Query string values
     * @return $this
     */
    public function query($query)
    {
        $this->applyRequestOptions(["query" => $query]);
        return $this;
    }
}