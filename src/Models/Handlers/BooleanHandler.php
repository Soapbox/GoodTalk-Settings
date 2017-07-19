<?php

namespace SoapBox\Settings\Models\Handlers;

class BooleanHandler extends Handler
{
    public function getValueAttribute(string $value)
    {
        return $value === 'true';
    }

    public function setValueAttribute(array &$attributes, $value)
    {
        $attributes['value'] = $value ? 'true' : 'false';
    }
}
