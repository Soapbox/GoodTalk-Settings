<?php

namespace SoapBox\Settings\Utilities;

use InvalidArgumentException;

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

        foreach ($keys as $key) {
            if (!preg_match('/^[\pL\pM\pN_-]+$/u', $key)) {
                throw new InvalidArgumentException();
            }
        }
    }
}
