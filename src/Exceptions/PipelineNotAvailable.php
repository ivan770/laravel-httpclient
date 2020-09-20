<?php

namespace Ivan770\HttpClient\Exceptions;

class PipelineNotAvailable extends ClientException
{
    protected function getBaseMessage(): string
    {
        return 'Pipeline class not found';
    }
}