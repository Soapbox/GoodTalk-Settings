<?php

use Faker\Generator;
use SoapBox\Settings\Models\SettingValue;

$factory->define(SettingValue::class, function (Generator $faker) {
    return [
        'identifier' => 'identifier',
        'value' => 'value',
    ];
});
