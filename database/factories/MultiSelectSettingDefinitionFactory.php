<?php

use Faker\Generator;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;

$factory->define(MultiSelectSettingDefinition::class, function (Generator $faker) {
    return [
        'group' => 'settings',
        'key' => 'key',
        'type' => MultiSelectSettingDefinition::class,
        'options' => ['option1', 'option2'],
        'value' => ['option1'],
    ];
});
