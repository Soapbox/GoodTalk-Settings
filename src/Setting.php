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

    /**
     * Set the group for this setting
     *
     * @param mixed value
     *
     * @return void
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * Get the group for this setting
     *
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * Get the key for this setting
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the identifier for this setting
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Get the value for this setting
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
