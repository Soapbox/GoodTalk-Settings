<?php
namespace Database\Factories;

use SoapBox\Settings\Models\TextSettingDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

class TextSettingDefinitionFactory extends Factory
{
    protected $model = TextSettingDefinition::class;

    public function definition()
    {
        return [
            'group' => 'settings',
            'key' => 'key',
            'type' => TextSettingDefinition::class,
            'value' => 'default',
            'options' => [],
        ];
    }
}
