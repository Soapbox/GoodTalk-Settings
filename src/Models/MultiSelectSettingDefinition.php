<?php

namespace SoapBox\Settings\Models;

use SoapBox\Settings\Models\Handlers\Handler;
use SoapBox\Settings\Models\Handlers\MultiSelectHandler;

class MultiSelectSettingDefinition extends SettingDefinition
{
    /**
     * Get the validation rules for this Handler
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

    public function getValueMutator(): Handler
    {
        return new MultiSelectHandler();
    }
}
