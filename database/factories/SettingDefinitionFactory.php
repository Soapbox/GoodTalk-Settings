<?php

use Faker\Generator;
use SoapBox\Settings\Models\SettingDefinition;

$factory->define(SettingDefinition::class, function (Generator $faker) {
    return [
        'group' => 'settings',
        'key' => 'key',
        'type' => 'text',
        'value' => 'default',
        'options' => [],
    ];
});

$factory->state(SettingDefinition::class, 'text', function (Generator $faker) {
    return [
        'type' => 'text',
    ];
});

$factory->state(SettingDefinition::class, 'boolean', function (Generator $faker) {
    return [
        'type' => 'boolean',
    ];
});

$factory->state(SettingDefinition::class, 'single-select', function (Generator $faker) {
    return [
        'type' => 'single-select',
    ];
});

$factory->state(SettingDefinition::class, 'multi-select', function (Generator $faker) {
    return [
        'type' => 'multi-select',
    ];
});
