<?php

namespace SoapBox\Settings\Builders\Updaters;

use SoapBox\Settings\Models\SettingDefinition;

abstract class SettingUpdater
{
    protected $definition;

    public function __construct(SettingDefinition $definition)
    {
        $this->definition = $definition;
    }
}
