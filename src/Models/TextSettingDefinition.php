<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Mutators\Mutator;
use SoapBox\Settings\Models\Mutators\TextMutator;
use Database\Factories\TextSettingDefinitionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TextSettingDefinition extends SettingDefinition
{
    use HasFactory;

    /**
     * Get the validation rules for this Mutator
     *
     * @return array
     */
    public function getRules(): array
    {
        $rules = [];

        if (!empty($this->validation)) {
            $rules['value'] = $this->validation;
        }

        return array_merge(parent::getRules(), $rules);
    }

    public function getValueMutator(): Mutator
    {
        return new TextMutator();
    }

    public static function newFactory()
    {
        return TextSettingDefinitionFactory::new();
    }
}
