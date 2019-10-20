<?php

namespace Ivan770\HttpClient;

use Illuminate\Contracts\Support\Arrayable;

class Builder
{
    /**
     * Request options, that will be passed to Symfony HttpClient
     *
     * @var array
     */
    protected $request = [];

    /**
     * Apply request options to current builder
     *
     * @param $options
     */
    public function applyRequestOptions($options)
    {
        $this->request = array_merge($this->request, $options);
    }

    /**
     * Reset request options
     */
    public function resetRequest()
    {
        $this->request = [];
    }

    /**
     * Get request options
     *
     * @return array
     */
    public function getRequest()
    {
        return $this->request;
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
     * @param array|string $credentials Authentication credentials
     * @return $this
     */
    public function authBasic($credentials)
    {
        $this->applyRequestOptions(['auth_basic' => $credentials]);
        return $this;
    }

    /**
     * Add Bearer token to request
     *
     * @param string $credentials Bearer token
     * @return $this
     */
    public function authBearer($credentials)
    {
        $this->applyRequestOptions(['auth_bearer' => $credentials]);
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
        $this->applyRequestOptions(['headers' => $headers]);
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
        $this->applyRequestOptions(['body' => $body]);
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
        $this->applyRequestOptions(['json' => $json]);
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
        $this->applyRequestOptions(['query' => $query]);
        return $this;
    }

    /**
     * Ignore all redirects for this request
     *
     * @return $this
     */
    public function withoutRedirects()
    {
        $this->applyRequestOptions(['max_redirects' => 0]);
        return $this;
    }

    /**
     * Change proxy for this request
     *
     * @param string $proxy Proxy value for CURLOPT_PROXY
     * @param string $noproxy Comma-separated list of hosts, that do not require proxy
     * @return $this
     */
    public function proxy($proxy = null, $noproxy = null)
    {
        $this->applyRequestOptions(['proxy' => $proxy, 'no_proxy' => $noproxy]);
        return $this;
    }

    /**
     * Parse Arrayable class as JSON data source
     *
     * @param Arrayable $arrayable Class to parse
     * @return $this
     */
    public function parse(Arrayable $arrayable)
    {
        $this->applyRequestOptions(['json' => $arrayable->toArray()]);
        return $this;
    }
}