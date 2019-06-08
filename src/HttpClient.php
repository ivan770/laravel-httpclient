<?php

namespace Ivan770\HttpClient;

use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpClient\HttpClient as Client;

/**
 * @method get(string $url, array $arguments) Send a GET request
 * @method head(string $url, array $arguments) Send a HEAD request
 * @method post(string $url, array $arguments) Send a POST request
 * @method put(string $url, array $arguments) Send a PUT request
 * @method delete(string $url, array $arguments) Send a DELETE request
 */
class HttpClient
{
    use Requestable, Macroable {
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