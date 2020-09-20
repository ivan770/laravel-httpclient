<?php


namespace Ivan770\HttpClient\Contracts;

use Closure;
use Illuminate\Support\Collection;
use Ivan770\HttpClient\Response\Response;

interface Request
{
    /**
     * Attach builder properties. HttpClient instance is passed into Closure
     *
     * @param Closure $callback
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
     * @return Collection|string
     */
    public function get();
}