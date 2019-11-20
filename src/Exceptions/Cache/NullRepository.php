<?php

namespace Ivan770\HttpClient\Exceptions\Cache;

use Exception;

class NullRepository extends Exception
{
    protected $message = "Cache repository was not provided";

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}