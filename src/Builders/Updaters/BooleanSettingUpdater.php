<?php

namespace SoapBox\Settings\Builders\Updaters;

class BooleanSettingUpdater extends SettingUpdater
{
    /**
     * Set the default value for the setting definition
     *
     * @param bool $default
     *
     * @return void
     */
    public function setDefault(bool $default): void
    {
        $this->definition->value = $default;
    }
}
