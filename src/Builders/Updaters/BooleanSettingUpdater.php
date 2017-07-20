<?php

namespace SoapBox\Settings\Builders\Updaters;

class BooleanSettingUpdater extends SettingUpdater
{
    public function setDefault(bool $default)
    {
        $this->definition->value = $default;
    }
}
