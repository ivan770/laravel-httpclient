<?php

namespace Ivan770\HttpClient\Exceptions;

class ContainerNotAvailable extends ClientException
{
    protected function getBaseMessage(): string
    {
        return 'Container not available';
    }
}