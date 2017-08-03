<?php

namespace SoapBox\Settings\Exceptions;

use Exception;

class InvalidGroupException extends Exception
{
    public function __construct(string $group = '', int $code = 0, Exception $previous = null)
    {
        parent::__construct(sprintf('The group "%s" is invalid.', $group), $code, $previous);
    }
}
