<?php

namespace SoapBox\Settings;

use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;

class Settings
{
    private function loadMany(string $group, Collection $identifiers)
    {
        $identifiers = $identifiers->filter(function ($identifier) use ($group) {
            return !isset($this->settingsCache[$group][$identifier]);
        });

        if ($identifiers->isEmpty()) {
            return;
        }

        $definitions = SettingDefinition::where('group', $group)->get();
        $overrides = SettingValue::getValuesForDefinitions($definitions, $identifiers);

        foreach ($identifiers as $identifier) {
            $dasOverrides = $overrides->get($identifier, new Collection());
            $settings = $definitions->map(function ($definition) use ($dasOverrides) {
                $setting = new Setting($definition);

                if ($dasOverrides->has($definition->id)) {
                    $setting->setValue($dasOverrides->get($definition->id));
                }

                return $setting;
            })->keyBy(function ($setting) {
                return $setting->getKey();
            });

            $this->settingsCache[$group][$identifier] = $settings;
        }
    }

    private function load(string $group, string $identifier)
    {
        $this->loadMany($group, new Collection($identifier));
    }

    public function get(string $group, string $identifier)
    {
        $this->load($group, $identifier);
        return $this->settingsCache[$group][$identifier];
    }

    public function getMany(string $group, Collection $identifiers)
    {
        $this->loadMany($group, $identifiers);

        $results = new Collection();

        foreach ($identifiers as $identifier) {
            $results->put($identifier, $this->settingsCache[$group][$identifier]);
        }

        return $results;
    }
}
