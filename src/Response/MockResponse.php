<?php

namespace Ivan770\HttpClient\Response;

use Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Ivan770\HttpClient\Contracts\Response;
use Ivan770\HttpClient\Exceptions\DataIsNotCollection;
use Ivan770\HttpClient\Exceptions\PipelineNotAvailable;

class MockResponse implements Response
{
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
     * @var Container
     */
    protected $container;

    /**
     * @var Pipeline
     */
    protected $pipeline;

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
     * Check if Pipeline is available
     *
     * @return bool
     * @throws PipelineNotAvailable
     */
    protected function pipelineAvailable()
    {
        if (class_exists(Pipeline::class)) {
            return true;
        }
        throw new PipelineNotAvailable("Pipeline class cannot be found");
    }

    /**
     * Get container instance
     *
     * @return Container|null
     */
    protected function getContainer()
    {
        if (is_null($this->container) && class_exists(Container::class)) {
            $this->container = Container::getInstance();
        }
        return $this->container;
    }

    /**
     * Get Pipeline instance
     *
     * @return Pipeline
     * @throws PipelineNotAvailable
     */
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

        throw new DataIsNotCollection("Passed data has to be collection instance");
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
     * @throws PipelineNotAvailable
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