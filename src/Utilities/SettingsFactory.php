<?php

namespace SoapBox\Settings\Utilities;

use Illuminate\Support\Collection;

class SettingsFactory
{
    /**
     * Create a settings collection keyed by the setting key
     *
     * @param string $identifier
     * @param \Illuminate\Support\Collection $definitions
     *        A collection of \SoapBox\Settings\Models\SettingDefinition objects
     * @param \Illuminate\Support\Collection $overrides
     *        A collection of \SoapBox\Settings\Models\SettingValue objects
     *
     * @return \Illuminate\Support\Collection
     *         A collection of \SoapBox\Settings\Setting objects
     */
    public static function make(string $identifier, Collection $definitions, Collection $overrides): Collection
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
