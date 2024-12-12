<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;

class MultiSelectSettingDefinitionFactory extends Factory
{
    protected $model = MultiSelectSettingDefinition::class;

    public function definition() : array
    {
        return [
            'group' => 'settings',
            'key' => 'key',
            'type' => MultiSelectSettingDefinition::class,
            'options' => ['option1', 'option2'],
            'value' => ['option1'],
        ];
    }
}
