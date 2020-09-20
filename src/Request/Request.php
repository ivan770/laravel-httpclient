<?php


namespace Ivan770\HttpClient\Request;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Ivan770\HttpClient\Contracts\PassToBrowserKit;
use Ivan770\HttpClient\Contracts\Request as RequestContract;
use Ivan770\HttpClient\Exceptions\Cache\BrowserKitCache;
use Ivan770\HttpClient\Exceptions\Cache\NullRepository;
use Ivan770\HttpClient\HttpClient;
use Ivan770\HttpClient\Response\Response;
use JsonSerializable;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\DomCrawler\Crawler;
use Traversable;

/**
 * @method RequestContract auth(string $type, array|string $credentials) Authentication credentials
 * @method RequestContract authBasic(array|string $credentials) Add HTTP basic auth to request
 * @method RequestContract authBearer(string $credentials) Add Bearer token to request
 * @method RequestContract headers(array $headers) Add headers to request
 * @method RequestContract body(array|string|resource|Traversable|Closure $body) Add body to request
 * @method RequestContract json(array|JsonSerializable $json) Add JSON to request
 * @method RequestContract query(array $query) Add query string values to request
 * @method RequestContract withoutRedirects() Ignore all redirects for this request
 * @method RequestContract proxy(string $proxy, string $noproxy) Change proxy for this request
 * @method RequestContract parse(Arrayable $arrayable) Parse Arrayable class as JSON data source
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
     * Cache repository instance
     *
     * @var Repository
     */
    protected $repository;

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
     * Request cache key
     *
     * @var mixed
     */
    protected $cacheKey;

    /**
     * @param HttpClient $client
     * @param Repository|null $repository
     */
    public function __construct(HttpClient $client, Repository $repository = null)
    {
        $this->client = $client;
        $this->repository = $repository;

        $this->defaultAttach($this->client);
    }

    /**
     * Should request use BrowserKit
     *
     * @return bool
     */
    protected function browserKit()
    {
        return $this instanceof PassToBrowserKit;
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
     * Method getter
     *
     * @return string
     */
    public function getMethod()
    {
        if($this->browserKit()) {
            return strtoupper($this->method);
        }

        return strtolower($this->method);
    }

    /**
     * Resource getter
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Cache key getter
     *
     * @return mixed
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * Attach builder properties. HttpClient instance is passed into Closure
     *
     * @param Closure $callback
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
     * @return Response|Crawler
     */
    public function execute()
    {
        $method = $this->getMethod();

        if($this->browserKit()) {
            $this->wrapBrowserKit($this->client, new History(), new CookieJar());
            return $this->passToBrowserKit($method, $this->getResource());
        }

        return $this->client->$method($this->getResource());
    }

    /**
     * Run request, and retrieve response contents if possible
     *
     * @return Collection|string|Crawler
     */
    public function get()
    {
        if($this->browserKit()) {
            return $this->execute();
        }

        return $this->execute()->getContent();
    }

    /**
     * Get cached response, or run request and save response contents to cache
     *
     * @param $ttl
     * @return mixed
     * @throws BrowserKitCache
     * @throws NullRepository
     */
    public function remember($ttl)
    {
        if($this->browserKit()) {
            throw new BrowserKitCache();
        }

        if($this->repository === null) {
            throw new NullRepository();
        }

        return $this->repository->remember($this->getCacheKey(), $ttl, function () {
            return $this->get();
        });
    }

    public function __call($name, $arguments)
    {
        $this->client->$name(...$arguments);

        return $this;
    }
}