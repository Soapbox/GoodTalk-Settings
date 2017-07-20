<?php

use Faker\Generator;
use SoapBox\Settings\Models\BooleanSettingDefinition;

$factory->define(BooleanSettingDefinition::class, function (Generator $faker) {
    return [
        'group' => 'settings',
        'key' => 'key',
        'type' => BooleanSettingDefinition::class,
        'value' => true,
        'options' => [],
    ];
});
