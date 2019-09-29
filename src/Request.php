<?php


namespace Ivan770\HttpClient;

use Ivan770\HttpClient\Contracts\Request as RequestContract;

abstract class Request implements RequestContract
{
    /**
     * HttpClient instance
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * Request URL
     *
     * @var string
     */
    protected $resource;

    /**
     * Request method
     *
     * @var string
     */
    protected $method = 'GET';

    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Method getter
     *
     * @return string
     */
    protected function getMethod()
    {
        return strtolower($this->method);
    }

    /**
     * Resource getter
     *
     * @return string
     */
    protected function getResource()
    {
        return $this->resource;
    }

    /**
     * Attach builder properties on execution
     *
     * @param HttpClient $client
     */
    protected function defaultAttach(HttpClient $client)
    {
        //
    }

    /**
     * Request tests
     *
     * @return array
     */
    protected function tests()
    {
        return [];
    }

    /**
     * Attach builder properties. HttpClient instance is passed into Closure
     *
     * @param \Closure $callback
     * @return Request
     */
    public function attach($callback)
    {
        $callback($this->client);

        return $this;
    }

    /**
     * Get test response
     *
     * @param $test
     * @return mixed
     */
    public function mock($test)
    {
        return $this->tests()[$test];
    }

    /**
     * Run request
     *
     * @return Response
     */
    public function execute()
    {
        $this->defaultAttach($this->client);

        $method = $this->getMethod();

        return $this->client->$method($this->getResource());
    }

    /**
     * Run request, and retrieve response contents
     *
     * @return \Illuminate\Support\Collection|string
     */
    public function get()
    {
        return $this->execute()->getContent();
    }
}