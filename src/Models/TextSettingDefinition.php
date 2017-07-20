<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Handlers\Handler;
use SoapBox\Settings\Models\Handlers\TextHandler;

class TextSettingDefinition extends SettingDefinition
{
    public function getValueMutator(): Handler
    {
        return new TextHandler();
    }
}
