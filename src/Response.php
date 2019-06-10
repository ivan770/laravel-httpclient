<?php

namespace Ivan770\HttpClient;

use Ivan770\HttpClient\Exceptions\PipelineNotAvailable;
use Symfony\Component\HttpClient\Exception\JsonException;
use Illuminate\Pipeline\Pipeline;

/**
 * @method int getStatusCode() Get response status code
 * @method array getHeaders(bool $throw = true) Get response headers
 * @method array toArray(bool $throw = true) Get array from response
 * @method array|mixed|null getInfo(string $type = null) Get info from transport layer
 */
class Response
{
    protected $baseResponse;

    protected $pipeline;

    public function __construct($baseResponse)
    {
        $this->baseResponse = $baseResponse;
    }

    protected function pipelineAvailable()
    {
        //TODO: Remove pipeline vendor lock.
        if (class_exists(Pipeline::class)) {
            return true;
        }
        throw new PipelineNotAvailable("Pipeline class cannot be found");
    }

    protected function getPipeline()
    {
        if ($this->pipelineAvailable() && is_null($this->pipeline)) {
            $this->pipeline = new Pipeline();
        }
        return $this->pipeline;
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

    /**
     * Pass response content to function
     *
     * @param \Closure $function Function to call
     */
    public function then($function)
    {
        return $function->call($this, $this->getContent());
    }

    /**
     * Pass response content to pipeline
     *
     * @return Pipeline
     */
    public function pipeline()
    {
        return $this->getPipeline()->send($this->getContent());
    }

    public function __call($name, $arguments)
    {
        return $this->baseResponse->$name(...$arguments);
    }
}