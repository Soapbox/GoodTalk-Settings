<?php

namespace SoapBox\Settings;

use SoapBox\Settings\Models\SettingDefinition;

class Setting
{
    private $group;
    private $identifier;
    private $value;
    private $key;

    public function __construct(SettingDefinition $definition, string $identifier)
    {
        $this->group = $definition->group;
        $this->identifier = $identifier;
        $this->key = $definition->key;
        $this->value = $definition->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
