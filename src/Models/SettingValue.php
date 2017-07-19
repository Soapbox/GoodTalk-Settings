<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class SettingValue extends Model
{
    public static function getValuesForDefinitions(Collection $definitions, Collection $identifiers): Collection
    {
        return self::whereIn('setting_definition_id', $definitions->pluck('id'))
            ->whereIn('identifier', $identifiers->toArray())
            ->get();
    }
}
