<?php
namespace Database\Factories;

use SoapBox\Settings\Models\SettingDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingDefinitionFactory extends Factory
{
    protected $model = SettingDefinition::class;

    public function definition()
    {
        return [
            'group' => 'settings',
            'key' => 'key',
            'type' => 'type',
            'value' => 'default',
            'options' => [],
        ];
    }
}
