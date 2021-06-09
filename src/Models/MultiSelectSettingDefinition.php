<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Mutators\Mutator;
use SoapBox\Settings\Models\Mutators\MultiSelectMutator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SoapBox\Settings\Database\Factories\MultiSelectSettingDefinitionFactory;

class MultiSelectSettingDefinition extends SettingDefinition
{
    use HasFactory;
    /**
     * Get the validation rules for this Mutator
     *
     * @return array
     */
    public function getRules(): array
    {
        $rules = [
            'value' => 'array',
            'value.*' => 'in_array:options.*',
        ];

        return array_merge(parent::getRules(), $rules);
    }

    public function getValueMutator(): Mutator
    {
        return new MultiSelectMutator();
    }

    protected static function newFactory()
    {
        return MultiSelectSettingDefinitionFactory::new();
    }
}
