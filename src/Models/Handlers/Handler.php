<?php

namespace SoapBox\Settings\Models\Handlers;

abstract class Handler
{
    public function getRules(): array
    {
        return [];
    }

    public function getValueAttribute(string $value)
    {
        return $value;
    }

    public function setValueAttribute(array &$attributes, $value): void
    {
        $attributes['value'] = $value;
    }
}
