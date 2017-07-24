<?php

namespace SoapBox\Settings;

use SoapBox\Settings\Models\SettingDefinition;

class Setting
{
    private $group;
    private $key;
    private $identifier;
    private $value;
    private $type = '';
    private $options = [];

    public function __construct(string $group, string $key, string $identifier, $value)
    {
        $this->group = $group;
        $this->key = $key;
        $this->identifier = $identifier;
        $this->value = $value;
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

    /**
     * Set the type for this setting
     *
     * @param string $type
     *
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Get the type for this setting
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the options for this setting
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * Get the array of options for this setting
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
