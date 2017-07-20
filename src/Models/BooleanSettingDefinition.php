<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Handlers\Handler;
use SoapBox\Settings\Models\Handlers\BooleanHandler;

class BooleanSettingDefinition extends SettingDefinition
{
    public function getValueMutator(): Handler
    {
        return new BooleanHandler();
    }
}
