<?php

namespace SoapBox\Settings\Utilities;

use Illuminate\Support\Facades\Validator;

class KeyValidator
{
    /**
     * Ensure the given key passes the alpha-dash validation
     *
     * @param string|array $keys
     *
     * @return void
     */
    public static function validate($keys): void
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        Validator::make(['keys' => $keys], ['keys.*' => 'alpha-dash'])->validate();
    }
}
