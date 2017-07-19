<?php

namespace SoapBox\Settings\Builders;

use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use SoapBox\Settings\Models\SettingDefinition;

class Settings
{
    /**
     * Create a new text setting
     */
    public static function text(string $group, string $key, string $default)
    {
        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'text',
            'options' => [],
            'value' => $default,
        ]);
    }

    public static function boolean(string $group, string $key, bool $default)
    {
        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'boolean',
            'options' => [],
            'value' => $default,
        ]);
    }

    public static function singleSelect(string $group, string $key, array $options, string $default)
    {
        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'single-select',
            'options' => $options,
            'value' => $default,
        ]);
    }

    public static function multiSelect(string $group, string $key, array $options, array $default)
    {
        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'multi-select',
            'options' => $options,
            'value' => $default,
        ]);
    }
}
