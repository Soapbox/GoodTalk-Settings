<?php

use Faker\Generator;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Models\BooleanSettingDefinition;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

$factory->define(SettingDefinition::class, function (Generator $faker) {
    return [
        'group' => 'settings',
        'key' => 'key',
        'type' => 'type',
        'value' => 'default',
        'options' => [],
    ];
});
