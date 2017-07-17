<?php

use Faker\Generator;
use SoapBox\Settings\Models\SettingDefinition;

$factory->define(SettingDefinition::class, function (Generator $faker) {
    return [
        'group' => 'settings',
        'key' => 'key',
        'value' => 'default',
    ];
});
