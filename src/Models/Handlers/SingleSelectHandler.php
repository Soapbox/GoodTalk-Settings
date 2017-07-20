<?php

namespace SoapBox\Settings\Models\Handlers;

class SingleSelectHandler extends Handler
{
    public function getRules(): array
    {
        return [
            'value' => 'in_array:options.*',
        ];
    }
}
