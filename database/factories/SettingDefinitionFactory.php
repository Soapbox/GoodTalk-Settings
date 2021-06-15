<?php
namespace SoapBox\Settings\Database\Factories;

use SoapBox\Settings\Models\SettingDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingDefinitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SettingDefinition::class;

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
            'type' => 'type',
            'value' => 'default',
            'options' => [],
        ];
    }
}
