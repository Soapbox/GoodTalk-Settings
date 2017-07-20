<?php

namespace SoapBox\Settings\Models\Mutators;

class BooleanMutator extends Mutator
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
