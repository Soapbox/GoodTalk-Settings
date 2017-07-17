<?php

namespace SoapBox\Settings;

use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;

class Setting
{
    private $key;
    private $value;
    private $identifier;

    public function __construct(SettingDefinition $definition, string $identifier)
    {
        $this->key = $definition->key;
        $this->value = $definition->value;
        $this->identifier = $identifier;
    }

    public function setValue(SettingValue $setting)
    {
        $this->value = $setting->value;
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public function getKey() : string
    {
        return $this->key;
    }

    public function getIdentifier() : string
    {
        return $this->identifier;
    }
}
