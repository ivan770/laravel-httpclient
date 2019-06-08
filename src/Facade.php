<?php

namespace Ivan770\HttpClient;

use Illuminate\Support\Facades\Facade as Base;

class Facade extends Base
{
    protected static function getFacadeAccessor()
    {
        return "HttpClient";
    }
}