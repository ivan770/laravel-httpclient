<?php

namespace Ivan770\HttpClient;

use Symfony\Component\HttpClient\Exception\JsonException;

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