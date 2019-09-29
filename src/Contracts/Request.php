<?php


namespace Ivan770\HttpClient\Contracts;

use Ivan770\HttpClient\Response;

interface Request
{
    /**
     * Attach builder properties. HttpClient instance is passed into Closure
     *
     * @param \Closure $callback
     * @return Request
     */
    public function attach($callback);

    /**
     * Run request
     *
     * @return Response
     */
    public function execute();

    /**
     * Run request, and retrieve response contents
     *
     * @return \Illuminate\Support\Collection|string
     */
    public function get();
}