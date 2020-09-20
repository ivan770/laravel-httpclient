<?php

namespace Ivan770\HttpClient\Exceptions\Cache;

use Ivan770\HttpClient\Exceptions\ClientException;

class BrowserKitCache extends ClientException
{
    protected function getBaseMessage(): string
    {
        return 'BrowserKit caching is not supported';
    }
}