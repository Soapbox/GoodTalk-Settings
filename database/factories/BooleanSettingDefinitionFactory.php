<?php
namespace SoapBox\Settings\Database\Factories;

use SoapBox\Settings\Models\BooleanSettingDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

class BooleanSettingDefinitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BooleanSettingDefinition::class;

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
            'type' => BooleanSettingDefinition::class,
            'value' => true,
            'options' => [],
        ];
    }
}
