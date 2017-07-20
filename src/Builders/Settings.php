<?php

namespace SoapBox\Settings\Builders;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Models\BooleanSettingDefinition;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

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
    public static function text(string $group, string $key, string $default, string $validation = ''): void
    {
        TextSettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'validation' => $validation,
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
        BooleanSettingDefinition::create([
            'group' => $group,
            'key' => $key,
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
        SingleSelectSettingDefinition::create([
            'group' => $group,
            'key' => $key,
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
        MultiSelectSettingDefinition::create([
            'group' => $group,
            'key' => $key,
            'options' => $options,
            'value' => $default,
        ]);
    }

    /**
     * Ensure that there are overrides for each of the given identifiers for the
     * the setting identified by the given group and key
     *
     * @param string $group
     * @param string $key
     * @param \Illuminate\Support\Collection $identifiers
     *
     * @return void
     */
    public static function ensureHasOverride(string $group, string $key, Collection $identifiers): void
    {
        $definition = SettingDefinition::getDefinition($group, $key);

        $existingOverrides = $definition->values->keyBy('identifier');

        $identifiers->filter(function ($identifier) use ($existingOverrides) {
            return !$existingOverrides->has($identifier);
        })->each(function ($identifier) use ($definition) {
            SettingValue::create(
                $definition,
                ['value' => $definition->value, 'identifier' => $identifier]
            );
        });
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
        $definition = SettingDefinition::getDefinition($group, $key);

        $class = substr($definition->type, strrpos($definition->type, '\\') + 1);
        $class = sprintf('%s\Updaters\%sUpdater', __NAMESPACE__, $class);
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
