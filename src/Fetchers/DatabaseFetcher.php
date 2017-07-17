<?php

namespace SoapBox\Settings\Fetchers;

use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\Eloquent\SettingValue;
use SoapBox\Settings\Models\Eloquent\SettingDefinition;

class DatabaseFetcher implements SettingFetcher
{
    private function mergeOverrides(Collection $definitions, Collection $overrides, string $identifier)
    {
        return $definitions->map(function ($definition) use ($overrides, $identifier) {
            $setting = new Setting($definition, $identifier);

            if ($overrides->has($definition->id)) {
                $setting->setValue($overrides->get($definition->id));
            }

            return $setting;
        });
    }

    public function get(string $group, string $identifier)
    {
        return $this->getMultiple($group, new Collection($identifier));
    }

    public function getMultiple(string $group, Collection $identifiers)
    {
        $definitions = SettingDefinition::getForGroup($group);
        $values = SettingValue::getValuesForDefinitions($definitions, $identifiers);

        return $identifiers->keyBy(function ($identifier) {
            return $identifier;
        })->map(function ($identifier) use ($definitions, $values) {
            $overrides = $values->get($identifier, new Collection());
            return $this->mergeOverrides($definitions, $overrides, $identifier)
                ->keyBy(function ($setting) {
                    return $setting->getKey();
                });
        });
    }
}
