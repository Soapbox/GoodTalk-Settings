<?php

namespace SoapBox\Settings;

use SoapBox\Settings\Utilities\KeyValidator;
use SoapBox\Settings\Models\SettingDefinition;

class Setting
{
    private $group;
    private $key;
    private $identifier;
    private $value;
    private $type = '';
    private $options = [];
    private $isDefaultValue = true;

    /**
     * Create a new Setting object
     *
     * @throws \Illuminate\Validation\ValidationException
     *         When the group, key or identifier fail to pass validation. These
     *         values must only contain characters in the set [a-zA-Z0-9-_].
     *
     * @param string $group
     * @param string $key
     * @param string $identifier
     * @param mixed $value
     */
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
     * Set whether the value is Default or not for this setting
     * @param boolean
     *
     * @return void
     */
    public function setIsDefaultValue($isDefaultValue): void
    {
        $this->isDefaultValue = $isDefaultValue;
    }

    /**
     * Get whether the value is the Default value or not
     *
     * @return bool
     */
    public function getIsDefaultValue(): bool
    {
        return $this->isDefaultValue;
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
