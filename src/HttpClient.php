<?php

namespace Ivan770\HttpClient;

use Illuminate\Support\Traits\Macroable;
use Ivan770\HttpClient\Traits\Buildable;
use Ivan770\HttpClient\Traits\InteractsWithEloquent;
use Ivan770\HttpClient\Traits\Requestable;
use Symfony\Component\HttpClient\HttpClient as Client;

/**
 * @method Response get(string $url, array $arguments) Send a GET request
 * @method Response head(string $url, array $arguments) Send a HEAD request
 * @method Response post(string $url, array $arguments) Send a POST request
 * @method Response put(string $url, array $arguments) Send a PUT request
 * @method Response delete(string $url, array $arguments) Send a DELETE request
 */
class HttpClient
{
    use InteractsWithEloquent, Buildable, Requestable, Macroable {
        Macroable::__call as macroCall;
    }

    protected $client;

    public function __construct(Client $client, $clientArgs = [])
    {
        $this->client = $client->create($clientArgs);
    }

    public function __call($name, $arguments)
    {
        if (static::hasMacro($name)) {
            return $this->macroCall($name, $arguments);
        }

        if ($this->isRequestMethod($name)) {
            return $this->callRequestMethod($name, $arguments);
        }

        return $this->client->$name(...$arguments);
    }
}