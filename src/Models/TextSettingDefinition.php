<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Mutators\Mutator;
use SoapBox\Settings\Models\Mutators\TextMutator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SoapBox\Settings\Database\Factories\TextSettingDefinitionFactory;

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

    protected static function newFactory()
    {
        return TextSettingDefinitionFactory::new();
    }
}
