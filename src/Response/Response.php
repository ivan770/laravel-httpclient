<?php

namespace Ivan770\HttpClient\Response;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;
use Illuminate\Support\Collection;
use Ivan770\HttpClient\Contracts\Response as ResponseContract;
use Ivan770\HttpClient\Exceptions\ContainerNotAvailable;
use Ivan770\HttpClient\Exceptions\PipelineNotAvailable;
use Ivan770\HttpClient\Traits\Pipeable;
use Symfony\Component\HttpClient\Exception\JsonException;

/**
 * @method int getStatusCode() Get response status code
 * @method array getHeaders(bool $throw = true) Get response headers
 * @method array toArray(bool $throw = true) Get array from response
 * @method array|mixed|null getInfo(string $type = null) Get info from transport layer
 */
class Response implements ResponseContract
{
    use Pipeable;

    protected $baseResponse;

    public function __construct($baseResponse)
    {
        $this->baseResponse = $baseResponse;
    }

    /**
     * Create collection from response
     *
     * @return Collection
     */
    public function toCollection()
    {
        return Collection::make($this->baseResponse->toArray());
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
     * @param Closure $function Function to call
     * @return mixed
     */
    public function then($function)
    {
        return $function($this->getContent());
    }

    /**
     * Pass response content to pipeline
     *
     * @return PipelineContract
     * @throws BindingResolutionException
     * @throws ContainerNotAvailable
     * @throws PipelineNotAvailable
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