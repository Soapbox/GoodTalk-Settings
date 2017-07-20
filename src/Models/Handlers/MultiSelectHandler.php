<?php

namespace SoapBox\Settings\Models\Handlers;

use Illuminate\Support\Facades\Validator;

class MultiSelectHandler extends Handler
{
    /**
     * Get the validation rules for this Handler
     *
     * @return array
     */
    public function getRules(): array
    {
        return [
            'value' => 'array',
            'value.*' => 'in_array:options.*',
        ];
    }

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
