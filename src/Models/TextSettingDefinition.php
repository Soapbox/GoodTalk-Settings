<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Mutators\Mutator;
use SoapBox\Settings\Models\Mutators\TextMutator;

class TextSettingDefinition extends SettingDefinition
{
    public function getValueMutator(): Mutator
    {
        return new TextMutator();
    }
}
