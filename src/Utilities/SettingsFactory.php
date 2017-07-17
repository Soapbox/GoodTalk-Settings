<?php

namespace SoapBox\Settings\Utilities;

use Illuminate\Support\Collection;

class SettingsFactory
{
    public static function make(string $identifier, Collection $definitions, Collection $overrides)
    {
        $overrides = $overrides->keyBy(function ($override) {
            return $override->setting_definition_id;
        });

        return $definitions->map(function ($definition) use ($identifier, $overrides) {
            return SettingFactory::make($identifier, $definition, $overrides->get($definition->id));
        })->keyBy(function ($setting) {
            return $setting->getKey();
        });
    }
}
