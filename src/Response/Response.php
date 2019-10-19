<?php

namespace Ivan770\HttpClient\Response;

use Illuminate\Support\Collection;
use Ivan770\HttpClient\Exceptions\PipelineNotAvailable;
use Symfony\Component\HttpClient\Exception\JsonException;
use Illuminate\Container\Container;
use Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;
use Illuminate\Pipeline\Pipeline;
use Ivan770\HttpClient\Contracts\Response as ResponseContract;

/**
 * @method int getStatusCode() Get response status code
 * @method array getHeaders(bool $throw = true) Get response headers
 * @method array toArray(bool $throw = true) Get array from response
 * @method array|mixed|null getInfo(string $type = null) Get info from transport layer
 */
class Response implements ResponseContract
{
    protected $baseResponse;

    protected $pipeline;

    protected $container;

    public function __construct($baseResponse)
    {
        $this->baseResponse = $baseResponse;
    }

    protected function pipelineAvailable()
    {
        if (class_exists(Pipeline::class)) {
            return true;
        }
        throw new PipelineNotAvailable("Pipeline class cannot be found");
    }

    protected function getContainer()
    {
        if (is_null($this->container) && class_exists(Container::class)) {
            $this->container = Container::getInstance();
        }
        return $this->container;
    }

    protected function getPipeline()
    {
        if ($this->getContainer()->bound(PipelineContract::class)) {
            $this->pipeline = $this->getContainer()->make(PipelineContract::class);
        }
        if (is_null($this->pipeline) && $this->pipelineAvailable()) {
            $this->pipeline = new Pipeline($this->getContainer());
        }
        return $this->pipeline;
    }

    /**
     * Create collection from response
     *
     * @return Collection
     */
    public function toCollection()
    {
        return collect($this->baseResponse->toArray());
    }

    /**
     * Get body of response, or collection, if response is JSON-compatible
     *
     * @param bool $throw
     * @return Collection|string
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
     * @return mixed
     */
    public function then($function)
    {
        return $function($this->getContent());
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