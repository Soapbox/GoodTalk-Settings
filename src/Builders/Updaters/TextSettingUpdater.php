<?php

namespace SoapBox\Settings\Builders\Updaters;

class TextSettingUpdater extends SettingUpdater
{
    public function setDefault(string $default)
    {
        $this->definition->value = $default;
    }
}
