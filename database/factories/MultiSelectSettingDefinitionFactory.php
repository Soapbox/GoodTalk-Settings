<?php
namespace SoapBox\Settings\Database\Factories;

use SoapBox\Settings\Models\MultiSelectSettingDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

class MultiSelectSettingDefinitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MultiSelectSettingDefinition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
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
