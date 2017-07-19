<?php

namespace SoapBox\Settings\Models\Handlers;

class SingleSelectHandler extends Handler
{
    public function getRules()
    {
        return [
            'value' => 'in_array:options.*',
        ];
    }
}
