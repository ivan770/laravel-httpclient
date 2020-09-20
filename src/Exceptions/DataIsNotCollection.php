<?php

namespace Ivan770\HttpClient\Exceptions;

class DataIsNotCollection extends ClientException
{
    protected function getBaseMessage(): string
    {
        return 'Passed data has to be collection instance';
    }
}
