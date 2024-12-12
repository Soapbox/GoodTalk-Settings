<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SoapBox\Settings\Models\BooleanSettingDefinition;

class BooleanSettingDefinitionFactory extends Factory
{
    protected $model = BooleanSettingDefinition::class;

    public function definition(): array
    {
        return [
            'group' => 'settings',
            'key' => 'key',
            'type' => BooleanSettingDefinition::class,
            'value' => true,
            'options' => [],
        ];
    }
}
