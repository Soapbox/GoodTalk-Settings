<?php

namespace SoapBox\Settings\Models\Mutators;

use Illuminate\Support\Facades\Validator;

class MultiSelectMutator extends Mutator
{
    /**
     * Deserialize the given value from the database
     *
     * @param string $value
     *
     * @return array
     */
    public function deserializeValue(string $value)
    {
        return json_decode($value, true);
    }

    /**
     * Serialize the given value for the database
     *
     * @param array $value
     *
     * @return string
     */
    public function serializeValue($value): string
    {
        return json_encode($value);
    }
}
