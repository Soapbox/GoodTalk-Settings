<?php

namespace SoapBox\Settings\Builders\Updaters;

class TextSettingUpdater extends SettingUpdater
{
    /**
     * Set the default value for the setting definition
     *
     * @param string $default
     *
     * @return void
     */
    public function setDefault(string $default)
    {
        $this->definition->value = $default;
    }
}
