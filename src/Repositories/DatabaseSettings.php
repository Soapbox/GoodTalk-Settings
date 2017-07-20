<?php

namespace SoapBox\Settings\Repositories;

use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingsGroupFactory;

class DatabaseSettings implements Settings
{
    /**
     * Get the settings for the given group and identifier
     *
     * @param string $group
     * @param string $identifier
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $group, string $identifier): Collection
    {
        return $this->getMultiple($group, new Collection($identifier))->get($identifier);
    }

    /**
     * Get the settings for the given and the identifiers
     *
     * @param string $group
     * @param \Illuminate\Support\Collection $identifiers
     *        A collection of strings
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMultiple(string $group, Collection $identifiers): Collection
    {
        $definitions = SettingDefinition::getForGroup($group);
        $values = SettingValue::getValuesForDefinitions($definitions, $identifiers);

        return SettingsGroupFactory::make($identifiers, $definitions, $values);
    }
}
