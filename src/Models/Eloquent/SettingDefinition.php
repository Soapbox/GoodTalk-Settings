<?php

namespace SoapBox\Settings\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SoapBox\Settings\Models\SettingDefinition as SettingDefinitionInterface;

class SettingDefinition extends Model implements SettingDefinitionInterface
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
