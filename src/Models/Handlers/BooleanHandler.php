<?php

namespace SoapBox\Settings\Models\Handlers;

class BooleanHandler extends Handler
{
    /**
     * Deserialize the given value from the database
     *
     * @param string $value
     *
     * @return bool
     */
    public function deserializeValue(string $value)
    {
        return $value === 'true';
    }

    /**
     * Serialize the given value for the database
     *
     * @param bool $value
     *
     * @return string
     */
    public function serializeValue($value): string
    {
        return $value ? 'true' : 'false';
    }
}
