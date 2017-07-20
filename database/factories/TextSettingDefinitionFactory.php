<?php

use Faker\Generator;
use SoapBox\Settings\Models\TextSettingDefinition;

$factory->define(TextSettingDefinition::class, function (Generator $faker) {
    return [
        'group' => 'settings',
        'key' => 'key',
        'type' => TextSettingDefinition::class,
        'value' => 'default',
        'options' => [],
    ];
});
