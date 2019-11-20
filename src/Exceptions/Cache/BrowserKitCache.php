<?php

namespace Ivan770\HttpClient\Exceptions\Cache;

use Exception;

class BrowserKitCache extends Exception
{
    protected $message = "BrowserKit caching is not supported";

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}