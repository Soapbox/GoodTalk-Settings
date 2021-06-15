<?php
namespace SoapBox\Settings\Database\Factories;

use SoapBox\Settings\Models\SettingValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SettingValue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'identifier' => 'identifier',
            'value' => 'value',
        ];
    }
}
