<?php

namespace Ivan770\HttpClient\Exceptions;

use Exception;

abstract class ClientException extends Exception
{
    /**
     * Get base exception message, without any context
     *
     * @return string
     */
    abstract protected function getBaseMessage(): string;

    public function __toString() {
        return sprintf('%s: %s', __CLASS__, $this->getBaseMessage());
    }
}