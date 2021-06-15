<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Mutators\Mutator;
use SoapBox\Settings\Models\Mutators\BooleanMutator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SoapBox\Settings\Database\Factories\BooleanSettingDefinitionFactory;

class BooleanSettingDefinition extends SettingDefinition
{
    use HasFactory;

    public function getValueMutator(): Mutator
    {
        return new BooleanMutator();
    }

    protected static function newFactory()
    {
        return BooleanSettingDefinitionFactory::new();
    }
}
