<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

class SingleSelectSettingDefinitionFactory extends Factory
{
    protected $model = SingleSelectSettingDefinition::class;

    public function definition() : array
    {
        return [
            'group' => 'settings',
            'key' => 'key',
            'type' => SingleSelectSettingDefinition::class,
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ];
    }
}
