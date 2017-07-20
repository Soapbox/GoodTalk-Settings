<?php

namespace SoapBox\Settings\Models\Handlers;

abstract class Handler
{
    public function getRules(): array
    {
        return [];
    }

    public function deserializeValue(string $value)
    {
        return $value;
    }

    public function serializeValue($value): string
    {
        return $value;
    }
}
