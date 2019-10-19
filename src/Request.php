<?php


namespace Ivan770\HttpClient;

use Ivan770\HttpClient\Contracts\Request as RequestContract;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\History;

/**
 * @method RequestContract auth(string $type, array|string $credentials) Authentication credentials
 * @method RequestContract authBasic(array|string $credentials) Add HTTP basic auth to request
 * @method RequestContract authBearer(string $credentials) Add Bearer token to request
 * @method RequestContract headers(array $headers) Add headers to request
 * @method RequestContract body(array|string|resource|\Traversable|\Closure $body) Add body to request
 * @method RequestContract json(array|\JsonSerializable $json) Add JSON to request
 * @method RequestContract query(array $query) Add query string values to request
 * @method RequestContract withoutRedirects() Ignore all redirects for this request
 * @method RequestContract proxy(string $proxy, string $noproxy) Change proxy for this request
 *
 * @see Builder
 */
abstract class Request extends BrowserKitRequest implements RequestContract
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

        $this->defaultAttach($this->client);
    }

    /**
     * Method getter
     *
     * @return string
     */
    protected function getMethod()
    {
        if($this->enableBrowserKit) {
            return strtoupper($this->method);
        }

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
     * @return Response|\Symfony\Component\DomCrawler\Crawler
     */
    public function execute()
    {
        $method = $this->getMethod();

        if($this->enableBrowserKit) {
            $this->wrapBrowserKit($this->client, new History(), new CookieJar());
            return $this->passToBrowserKit($this->getMethod(), $this->getResource());
        }

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

    public function __call($name, $arguments)
    {
        $this->client->$name(...$arguments);

        return $this;
    }
}