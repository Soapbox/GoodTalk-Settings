<?php

namespace SoapBox\Settings\Builders\Updaters;

class TextSettingDefinitionUpdater extends SettingUpdater
{
    /**
     * Set the default value for the setting definition
     *
     * @param string $default
     *
     * @return void
     */
    public function setDefault(string $default): void
    {
        $this->definition->value = $default;
    }

    /**
     * Set the custom validation rules for the setting definition
     *
     * @param string $validation
     *
     * @return void
     */
    public function setValidation(string $validation): void
    {
        $this->definition->validation = $validation;
    }
}
