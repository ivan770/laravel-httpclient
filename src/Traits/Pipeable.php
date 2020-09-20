<?php

namespace Ivan770\HttpClient\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;
use Illuminate\Container\Container;
use Ivan770\HttpClient\Exceptions\ContainerNotAvailable;
use Ivan770\HttpClient\Exceptions\PipelineNotAvailable;

trait Pipeable
{
    /**
     * Get global container instance
     *
     * @return ContainerContract
     * @throws ContainerNotAvailable
     */
    protected function getContainer(): ContainerContract
    {
        if (class_exists(Container::class)) {
            return Container::getInstance();
        }

        throw new ContainerNotAvailable();
    }

    /**
     * Get pipeline instance
     *
     * @return PipelineContract
     * @throws ContainerNotAvailable
     * @throws PipelineNotAvailable
     * @throws BindingResolutionException
     */
    protected function getPipeline(): PipelineContract
    {
        if ($this->getContainer()->bound(PipelineContract::class)) {
            return $this->getContainer()->make(PipelineContract::class);
        }

        throw new PipelineNotAvailable();
    }
}