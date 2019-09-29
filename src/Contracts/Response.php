<?php

namespace Ivan770\HttpClient\Contracts;

use Illuminate\Pipeline\Pipeline;

interface Response
{
    /**
     * Create collection from response
     *
     * @return \Illuminate\Support\Collection
     */
    public function toCollection();

    /**
     * Get body of response, or collection, if response is JSON-compatible
     *
     * @param bool $throw
     * @return \Illuminate\Support\Collection|string
     */
    public function getContent($throw = true);

    /**
     * Pass response content to function
     *
     * @param \Closure $function Function to call
     */
    public function then($function);

    /**
     * Pass response content to pipeline
     *
     * @return Pipeline
     */
    public function pipeline();
}