<?php

namespace Ivan770\HttpClient\Contracts;

use Closure;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Collection;

interface Response
{
    /**
     * Create collection from response
     *
     * @return Collection
     */
    public function toCollection();

    /**
     * Get body of response, or collection, if response is JSON-compatible
     *
     * @param bool $throw
     * @return Collection|string
     */
    public function getContent($throw = true);

    /**
     * Pass response content to function
     *
     * @param Closure $function Function to call
     */
    public function then($function);

    /**
     * Pass response content to pipeline
     *
     * @return Pipeline
     */
    public function pipeline();
}