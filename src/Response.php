<?php

namespace Ivan770\HttpClient;

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

    public function __call($name, $arguments)
    {
        return $this->baseResponse->$name(...$arguments);
    }
}