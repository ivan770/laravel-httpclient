<?php

namespace Ivan770\HttpClient;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use Ivan770\HttpClient\Response\Response;
use Ivan770\HttpClient\Traits\InteractsWithEloquent;
use Ivan770\HttpClient\Traits\Requestable;
use JsonSerializable;
use Symfony\Component\HttpClient\HttpClient as Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Traversable;

/**
 * @method HttpClient auth(string $type, array|string $credentials) Authentication credentials
 * @method HttpClient authBasic(array|string $credentials) Add HTTP basic auth to request
 * @method HttpClient authBearer(string $credentials) Add Bearer token to request
 * @method HttpClient headers(array $headers) Add headers to request
 * @method HttpClient body(array|string|resource|Traversable|Closure $body) Add body to request
 * @method HttpClient json(array|JsonSerializable $json) Add JSON to request
 * @method HttpClient query(array $query) Add query string values to request
 * @method HttpClient withoutRedirects() Ignore all redirects for this request
 * @method HttpClient proxy(string $proxy, string $noproxy) Change proxy for this request
 * @method HttpClient parse(Arrayable $arrayable) Parse Arrayable class as JSON data source
 * @method Response get(string $url, array $arguments = []) Send a GET request
 * @method Response head(string $url, array $arguments = []) Send a HEAD request
 * @method Response post(string $url, array $arguments = []) Send a POST request
 * @method Response put(string $url, array $arguments = []) Send a PUT request
 * @method Response delete(string $url, array $arguments = []) Send a DELETE request
 * @method Response connect(string $url, array $arguments = []) Send a CONNECT request
 * @method Response options(string $url, array $arguments = []) Send a OPTIONS request
 * @method Response trace(string $url, array $arguments = []) Send a TRACE request
 * @method Response patch(string $url, array $arguments = []) Send a PATCH request
 *
 * @see Builder
 */
class HttpClient
{
    use Requestable, Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var Builder
     */
    protected $builder;

    public function __construct(Client $client, Builder $builder, $clientArgs = [])
    {
        $this->client = $client->create($clientArgs);
        $this->builder = $builder;
    }

    /**
     * Get builder instance
     *
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Get Symfony HttpClient instance
     *
     * @return HttpClientInterface
     */
    public function getSymfonyClient()
    {
        return $this->client;
    }

    public function __call($name, $arguments)
    {
        if (static::hasMacro($name)) {
            return $this->macroCall($name, $arguments);
        }

        if ($this->isRequestMethod($name)) {
            return $this->callRequestMethod($name, $arguments);
        }

        if(method_exists($this->builder, $name)) {
            $this->builder->$name(...$arguments);

            return $this;
        }

        return $this->client->$name(...$arguments);
    }
}