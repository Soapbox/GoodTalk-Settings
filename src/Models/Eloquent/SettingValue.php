<?php

namespace SoapBox\Settings\Models\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use SoapBox\Settings\Models\SettingValue as SettingValueInterface;

class SettingValue extends Model implements SettingValueInterface
{
    public static function getValuesForDefinitions(Collection $definitions, Collection $identifiers)
    {
        return self::whereIn('setting_definition_id', $definitions->pluck('id'))
            ->whereIn('identifier', $identifiers->toArray())
            ->get()
            ->groupBy('identifier')
            ->map(function ($setting) {
                return $setting->keyBy('setting_definition_id');
            });
    }
}
