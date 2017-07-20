<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class SettingValue extends Model
{
    /**
     * Get all the setting values for the given definitions and identifiers
     *
     * @param \Illuminate\Support\Collection $definitions
     *        A collection of strings
     * @param \Illuminate\Support\Collection $identifiers
     *        A collection of strings
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getValuesForDefinitions(Collection $definitions, Collection $identifiers): Collection
    {
        return self::whereIn('setting_definition_id', $definitions->pluck('id'))
            ->whereIn('identifier', $identifiers->toArray())
            ->get();
    }
}
