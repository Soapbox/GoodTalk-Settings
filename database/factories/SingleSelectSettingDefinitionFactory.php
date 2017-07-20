<?php

use Faker\Generator;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

$factory->define(SingleSelectSettingDefinition::class, function (Generator $faker) {
    return [
        'group' => 'settings',
        'key' => 'key',
        'type' => SingleSelectSettingDefinition::class,
        'options' => ['option1', 'option2'],
        'value' => 'option1',
    ];
});
