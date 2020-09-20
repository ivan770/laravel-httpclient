<?php

namespace Ivan770\HttpClient\Facades;

use Illuminate\Support\Facades\Facade as Base;
use Ivan770\HttpClient\HttpClient as BaseClient;

class HttpClient extends Base
{
    protected static function getFacadeAccessor()
    {
        return BaseClient::class;
    }
}