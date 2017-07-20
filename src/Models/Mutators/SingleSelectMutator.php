<?php

namespace SoapBox\Settings\Models\Mutators;

class SingleSelectMutator extends Mutator
{
    /**
     * Get the validation rules for this Mutator
     *
     * @return array
     */
    public function getRules(): array
    {
        return [
            'value' => 'in_array:options.*',
        ];
    }
}
