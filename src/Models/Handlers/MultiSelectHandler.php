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
    public function getValueAttribute(string $value)
    {
        return json_decode($value, true);
    }

    public function setValueAttribute(array &$attributes, $value): void
    {
        $attributes['value'] = json_encode($value);
    }
}
