<?php

namespace SoapBox\Settings\Fetchers;

use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingsGroupFactory;

class DatabaseSettings implements Settings
{
    public function get(string $group, string $identifier)
    {
        return $this->getMultiple($group, new Collection($identifier))->get($identifier);
    }

    public function getMultiple(string $group, Collection $identifiers)
    {
        $definitions = SettingDefinition::getForGroup($group);
        $values = SettingValue::getValuesForDefinitions($definitions, $identifiers);

        return SettingsGroupFactory::make($identifiers, $definitions, $values);
    }
}
