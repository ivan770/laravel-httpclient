<?php

namespace Ivan770\HttpClient\Response;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Ivan770\HttpClient\Contracts\Response;
use Ivan770\HttpClient\Exceptions\ContainerNotAvailable;
use Ivan770\HttpClient\Exceptions\DataIsNotCollection;
use Ivan770\HttpClient\Exceptions\PipelineNotAvailable;
use Ivan770\HttpClient\Traits\Pipeable;

class MockResponse implements Response
{
    use Pipeable;

    /**
     * @var Collection|string
     */
    protected $data;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var array
     */
    protected $headers;

    /**
     * MockResponse constructor.
     *
     * @param Collection|string $data
     * @param int $status
     * @param array $headers
     */
    public function __construct($data, $status = 200, $headers = [])
    {
        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * Get passed collection
     *
     * @return Collection
     * @throws DataIsNotCollection
     */
    public function toCollection()
    {
        if($this->data instanceof Collection) {
            return $this->data;
        }

        throw new DataIsNotCollection();
    }

    /**
     * Get passed data
     *
     * @param bool $throw
     * @return Collection|string
     */
    public function getContent($throw = true)
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
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
     * @return Pipeline
     * @throws PipelineNotAvailable
     * @throws BindingResolutionException
     * @throws ContainerNotAvailable
     */
    public function pipeline()
    {
        return $this->getPipeline()->send($this->getContent());
    }

    /**
     * Make new MockResponse instance
     *
     * @param Collection|string $data
     * @param int $status
     * @param array $headers
     * @return MockResponse
     */
    public static function make($data, $status = 200, $headers = [])
    {
        return (new static($data, $status, $headers));
    }
}