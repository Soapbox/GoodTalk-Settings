<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Handlers\Handler;
use SoapBox\Settings\Models\Handlers\SingleSelectHandler;

class SingleSelectSettingDefinition extends SettingDefinition
{
    /**
     * Get the validation rules for this Handler
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

    public function getValueMutator(): Handler
    {
        return new SingleSelectHandler();
    }
}
