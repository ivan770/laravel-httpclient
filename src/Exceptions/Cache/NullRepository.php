<?php

namespace Ivan770\HttpClient\Exceptions\Cache;

use Ivan770\HttpClient\Exceptions\ClientException;

class NullRepository extends ClientException
{
    protected function getBaseMessage(): string
    {
        return 'Cache repository was not provided';
    }
}