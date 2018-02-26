<?php

namespace SoapBox\Settings\Utilities;

use Illuminate\Support\Arr;
use SoapBox\Settings\Setting;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Models\BooleanSettingDefinition;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

class SettingFactory
{
    private static $typeMap = [
        TextSettingDefinition::class => 'text',
        BooleanSettingDefinition::class => 'boolean',
        SingleSelectSettingDefinition::class => 'single-select',
        MultiSelectSettingDefinition::class => 'multi-select',
    ];

    /**
     * Create a setting object
     *
     * @param string $identifier
     * @param \SoapBox\Settings\Models\SettingDefinition $definition
     * @param \SoapBox\Settings\Models\SettingValue|null $value
     *
     * @return \SoapBox\Settings\Setting
     */
    public static function make(string $identifier, SettingDefinition $definition, SettingValue $value = null)
    {
        $setting = new Setting($definition->group, $definition->key, $identifier, $definition->value);

        $setting->setType(Arr::get(self::$typeMap, $definition->type, ''));
        $setting->setOptions($definition->options);

        if (!is_null($value)) {
            $setting->setValue($value->value);
            $setting->setIsDefaultValue(false);
        }

        return $setting;
    }
}
