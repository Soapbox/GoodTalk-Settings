<?php

namespace SoapBox\Settings\Models\Mutators;

abstract class Mutator
{
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
