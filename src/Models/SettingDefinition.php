<?php

namespace SoapBox\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingDefinition extends Model
{
    public static function getForGroup(string $group)
    {
        return self::where('group', $group)->get();
    }

    public function values() : HasMany
    {
        return $this->hasMany(SettingValue::class);
    }
}
