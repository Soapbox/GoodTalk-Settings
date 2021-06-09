<?php
namespace SoapBox\Settings\Database\Factories;

use SoapBox\Settings\Models\SingleSelectSettingDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

class SingleSelectSettingDefinitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SingleSelectSettingDefinition::class;

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
            'type' => SingleSelectSettingDefinition::class,
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ];
    }
}
