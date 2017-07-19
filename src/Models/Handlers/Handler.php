<?php

namespace SoapBox\Settings\Models\Handlers;

abstract class Handler
{
    public function getRules()
    {
        return [];
    }

    public function getValueAttribute(string $value)
    {
        return $value;
    }

    public function setValueAttribute(array &$attributes, $value)
    {
        $attributes['value'] = $value;
    }
}
