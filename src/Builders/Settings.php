<?php

namespace SoapBox\Settings\Builders;

use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use SoapBox\Settings\Models\SettingDefinition;

class Settings
{
    public static function text(string $group, string $key, string $default)
    {
        $data = [
            'group' => $group,
            'key' => $key,
            'type' => 'text',
            'options' => [],
            'value' => $default,
        ];

        $rules = [
            'group' => 'alpha-dash',
            'key' => 'alpha-dash',
        ];

        Validator::make($data, $rules)->validate();

        SettingDefinition::create($data);
    }

    public static function boolean(string $group, string $key, bool $default)
    {
        $data = [
            'group' => $group,
            'key' => $key,
            'type' => 'boolean',
            'options' => [],
            'value' => $default,
        ];

        $rules = [
            'group' => 'alpha-dash',
            'key' => 'alpha-dash',
            'value' => 'boolean',
        ];

        Validator::make($data, $rules)->validate();

        SettingDefinition::create($data);
    }

    public static function singleSelect(string $group, string $key, array $options, string $default)
    {
        $data = [
            'group' => $group,
            'key' => $key,
            'type' => 'single-select',
            'options' => $options,
            'value' => $default,
        ];

        $rules = [
            'group' => 'alpha-dash',
            'key' => 'alpha-dash',
            'options.*' => 'alpha-dash',
            'value' => 'in_array:options.*',
        ];

        Validator::make($data, $rules)->validate();

        SettingDefinition::create($data);
    }

    public static function multiSelect(string $group, string $key, array $options, array $default)
    {
        $data = [
            'group' => $group,
            'key' => $key,
            'type' => 'multi-select',
            'options' => $options,
            'value' => $default,
        ];

        $rules = [
            'group' => 'alpha-dash',
            'key' => 'alpha-dash',
            'options.*' => 'alpha-dash',
            'value.*' => 'in_array:options.*',
        ];

        Validator::make($data, $rules)->validate();

        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'multi-select',
            'options' => $options,
            'value' => $default,
        ]);
    }
}
