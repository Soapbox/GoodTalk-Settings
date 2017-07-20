<?php

namespace SoapBox\Settings\Models\Handlers;

use Illuminate\Support\Facades\Validator;

class MultiSelectHandler extends Handler
{
    public function getRules(): array
    {
        return [
            'value' => 'array',
            'value.*' => 'in_array:options.*',
        ];
    }
    public function deserializeValue(string $value)
    {
        return json_decode($value, true);
    }

    public function serializeValue($value): string
    {
        return json_encode($value);
    }
}
