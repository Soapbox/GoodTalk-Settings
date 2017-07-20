<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Mutators\Mutator;
use SoapBox\Settings\Models\Mutators\BooleanMutator;

class BooleanSettingDefinition extends SettingDefinition
{
    public function getValueMutator(): Mutator
    {
        return new BooleanMutator();
    }
}
