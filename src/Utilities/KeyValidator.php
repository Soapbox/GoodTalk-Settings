<?php

namespace SoapBox\Settings\Utilities;

use Illuminate\Support\Facades\Validator;

class KeyValidator
{
    /**
     * Ensure the given key passes the alpha-dash validation
     *
     * @param string $key
     *
     * @return void
     */
    public static function validate(string $key): void
    {
        Validator::make(['key' => $key], ['key' => 'alpha-dash'])->validate();
    }
}
