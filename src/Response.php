<?php

namespace Ivan770\HttpClient;

use Symfony\Component\HttpClient\Exception\JsonException;

/**
 * @method int getStatusCode() Get response status code
 * @method array getHeaders(bool $throw = true) Get response headers
 * @method array toArray(bool $throw = true) Get array from response
 * @method array|mixed|null getInfo(string $type = null) Get info from transport layer
 */
class Response
{
    protected $baseResponse;

    public function __construct($baseResponse)
    {
        $this->baseResponse = $baseResponse;
    }

    /**
     * Create collection from response
     *
     * @return \Illuminate\Support\Collection
     */
    public function toCollection()
    {
        return collect($this->baseResponse->toArray());
    }

    /**
     * Get body of response, or collection, if response is JSON-compatible
     *
     * @param bool $throw
     * @return \Illuminate\Support\Collection|string
     */
    public function getContent($throw = true)
    {
        try {
            return $this->toCollection();
        } catch (JsonException $exception) {
            return $this->baseResponse->getContent($throw);
        }
    }

    public function __call($name, $arguments)
    {
        return $this->baseResponse->$name(...$arguments);
    }
}