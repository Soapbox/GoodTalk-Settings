<?php

namespace SoapBox\Settings\Models\Handlers;

class SingleSelectHandler extends Handler
{
    /**
     * Get the validation rules for this Handler
     *
     * @return array
     */
    public function getRules(): array
    {
        return [
            'value' => 'in_array:options.*',
        ];
    }
}
