# Laravel HTTP client
## Installation
`composer require ivan770/laravel-httpclient`
## Usage
```php
// You can use Facade class to access HttpClient
use Ivan770\HttpClient\Facade;
// Or, you can obtain HttpClient instance directly
use Ivan770\HttpClient\HttpClient;
public function method(HttpClient $client)
```
### Sending requests
You can also use [Symfony HttpClient documentation](https://symfony.com/doc/current/components/http_client.html)
```php
$response = $client->get("https://example.com");
$response = $client->get("https://example.com", ["query" => ["key" => "value"]]);
$response->getContent(); // Get response body, or collection, if response is JSON
$response->toCollection(); // Transform JSON response to collection
$response->getStatusCode(); // Get response status code
$response->getHeaders(); // Get response headers

// You can use HTTP request methods as client methods
$client->head("https://example.com");
$client->post("https://example.com", ["body" => ["key" => "value"]]);
$client->post("https://example.com", ["json" => ["key" => "value"]]);
$client->put("https://example.com");
$client->delete("https://example.com");
```
### Using Request class
HttpClient provides ability to create "request classes".
```php
<?php

use Ivan770\HttpClient\Request;
use Ivan770\HttpClient\HttpClient;
use Ivan770\HttpClient\MockResponse;

class HttpBinGet extends Request
{
    // Request URL
    protected $resource = "https://httpbin.org/get";

    // Request method
    protected $method = "GET";

    // This method is called on request execution.
    // Here, you are able to use builder to modify your request
    protected function defaultAttach(HttpClient $client)
    {
	$client->authBearer("test");
    }

    protected function tests(){
        return [
            "success" => MockResponse::make("Hello World!"),
        ];
    }
}

// Execute request
app(HttpBinGet::class)->execute();

// Execute request and receive result
app(HttpBinGet::class)->get();

// Modify request using "attach" method.
app(HttpBinGet::class)->attach(function (HttpClient $client) {
    $client->headers(["test" => true]);
})->execute();

// Mock responses
$response = app(HttpBinGet::class)->mock("success");

$response->getContent(); // "Hello World!"
$response->getStatus(); // 200
$response->getHeaders(); // []
```
### Request builder
You can send your request parameters directly to client methods, but you can also use fluent request builder.
```php
// Add data to request
$client->query(["key" => "value"])->get("https://example.com")
$client->body(["key" => "value"])->post("https://example.com")
$client->json(["key" => "value"])->post("https://example.com")

// Add custom headers to request
$client->headers(["key" => "value"])->get("https://example.com");

// Ignore redirects
$client->withoutRedirects()->get("https://example.com");

// Proxy
$client->proxy("https://hostname:8080")->get("https://example.com");

// Authentication
$client->auth("auth_basic", ["username", "password"])->get("https://example.com");
$client->authBasic(["username", "password"])->get("https://example.com");
$client->authBearer("tokenhere")->get("https://example.com");
```

### Eloquent interaction
You can use your Eloquent models as data source for request
```php
$model = User::find(1);
$client->setModel($model)->fetchModel()->post("https://example.com");
// You can also use short variant
$client->fetchModel($model)->post("https://example.com");
```

### Data pipelining
If `illuminate/pipeline` is installed, you can send your data through pipelines.
If not, you can still pass your data to [Closure](https://www.php.net/manual/en/class.closure.php)
```php
$response = $client->get("https://example.com");

// Pass data to Closure
$response->then(function ($data) {
    return $data;
});

// Use Laravel pipelines
$pipes = [
    ExamplePipe::class
];
$response->pipeline()->through($pipes)->then(function ($data) {
    return $data;
});
```