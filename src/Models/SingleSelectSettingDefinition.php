<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Mutators\Mutator;
use SoapBox\Settings\Models\Mutators\SingleSelectMutator;

class SingleSelectSettingDefinition extends SettingDefinition
{
    /**
     * Get the validation rules for this Mutator
     *
     * @return array
     */
    public function getRules(): array
    {
        $rules = [
            'value' => 'in_array:options.*',
        ];

        return array_merge(parent::getRules(), $rules);
    }

    public function getValueMutator(): Mutator
    {
        return new SingleSelectMutator();
    }
}
