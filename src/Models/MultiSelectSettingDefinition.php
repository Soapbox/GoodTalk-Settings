<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Mutators\Mutator;
use SoapBox\Settings\Models\Mutators\MultiSelectMutator;

class MultiSelectSettingDefinition extends SettingDefinition
{
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
}
