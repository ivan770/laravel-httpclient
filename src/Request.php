<?php


namespace Ivan770\HttpClient;


abstract class Request
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