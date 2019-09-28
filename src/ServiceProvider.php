<?php

namespace Ivan770\HttpClient;

use Illuminate\Support\ServiceProvider as BaseProvider;
use Ivan770\HttpClient\Commands\HttpRequestMakeCommand;

class ServiceProvider extends BaseProvider
{
    public function boot()
    {
        $this->commands([
            HttpRequestMakeCommand::class
        ]);
    }
}