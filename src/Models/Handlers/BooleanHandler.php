<?php

namespace SoapBox\Settings\Models\Handlers;

class BooleanHandler extends Handler
{
    public function deserializeValue(string $value)
    {
        return $value === 'true';
    }

    public function serializeValue($value): string
    {
        return $value ? 'true' : 'false';
    }
}
