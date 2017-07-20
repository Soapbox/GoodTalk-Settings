<?php

namespace SoapBox\Settings\Models\Handlers;

abstract class Handler
{
    /**
     * Get the validation rules for this Handler
     *
     * @return array
     */
    public function getRules(): array
    {
        return [];
    }

    /**
     * Deserialize the given value from the database
     *
     * @param string $value
     *
     * @return mixed
     */
    public function deserializeValue(string $value)
    {
        return $value;
    }

    /**
     * Serialize the given value for the database
     *
     * @param mixed $value
     *
     * @return string
     */
    public function serializeValue($value): string
    {
        return $value;
    }
}
