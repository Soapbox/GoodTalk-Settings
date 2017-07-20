<?php

namespace SoapBox\Settings\Builders;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use SoapBox\Settings\Models\SettingDefinition;

class Settings
{
    /**
     * Create a new text setting
     *
     * @param string $group
     * @param string $key
     * @param string $default
     *
     * @return void
     */
    public static function text(string $group, string $key, string $default): void
    {
        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'text',
            'options' => [],
            'value' => $default,
        ]);
    }

    /**
     * Create a new boolean setting
     *
     * @param string $group
     * @param string $key
     * @param bool $default
     *
     * @return void
     */
    public static function boolean(string $group, string $key, bool $default): void
    {
        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'boolean',
            'options' => [],
            'value' => $default,
        ]);
    }

    /**
     * Create a new single select setting
     *
     * @param string $group
     * @param string $key
     * @param string[] $options
     * @param string $default
     *
     * @return void
     */
    public static function singleSelect(string $group, string $key, array $options, string $default): void
    {
        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'single-select',
            'options' => $options,
            'value' => $default,
        ]);
    }

    /**
     * Create a new multi select setting
     *
     * @param string $group
     * @param string $key
     * @param string[] $options
     * @param string[] $default
     *
     * @return void
     */
    public static function multiSelect(string $group, string $key, array $options, array $default): void
    {
        SettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'type' => 'multi-select',
            'options' => $options,
            'value' => $default,
        ]);
    }

    /**
     * Update a setting definition for the given group and key
     *
     * @param string $group
     * @param string $key
     * @param callable $callback
     *
     * @return void
     */
    public static function update(string $group, string $key, callable $callback): void
    {
        $definition = SettingDefinition::where('group', $group)
            ->where('key', $key)
            ->firstOrFail();

        $class = sprintf('%s\Updaters\%sSettingUpdater', __NAMESPACE__, Str::studly($definition->type));
        $updater = new $class($definition);

        $callback($updater);

        $definition->save();
        $definition->values->filter(function ($value) {
            return $value->isInvalid();
        })->each(function ($value) {
            $value->delete();
        });
    }
}
