<?php
namespace SoapBox\Settings\Database\Factories;

use SoapBox\Settings\Models\TextSettingDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

class TextSettingDefinitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TextSettingDefinition::class;

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
            'type' => TextSettingDefinition::class,
            'value' => 'default',
            'options' => [],
        ];
    }
}
