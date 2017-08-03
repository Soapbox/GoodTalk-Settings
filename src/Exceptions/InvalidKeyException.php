<?php

namespace SoapBox\Settings\Exceptions;

use Exception;

class InvalidKeyException extends Exception
{
    public function __construct(string $key = '', int $code = 0, Exception $previous = null)
    {
        parent::__construct(sprintf('The key "%s" is invalid.', $key), $code, $previous);
    }
}
