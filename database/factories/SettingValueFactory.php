<?php
namespace Database\Factories;

use SoapBox\Settings\Models\SettingValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingValueFactory extends Factory
{
    protected $model = SettingValue::class;

    public function definition()
    {
        return [
            'identifier' => 'identifier',
            'value' => 'value',
        ];
    }
}
