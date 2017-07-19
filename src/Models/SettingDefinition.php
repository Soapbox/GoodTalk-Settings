<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingDefinition extends Model
{
    protected $guarded = [];

    public static function getForGroup(string $group): Collection
    {
        return self::where('group', $group)->get();
    }

    public function values(): HasMany
    {
        return $this->hasMany(SettingValue::class);
    }

    protected function setOptionsAttribute(array $value)
    {
        $this->attributes['options'] = implode(',', $value);
    }

    protected function getOptionsAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }

        return explode(',', $value);
    }

    protected function setValueAttribute($value)
    {
        if ($this->type === 'multi-select') {
            $this->attributes['value'] = implode(',', $value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    protected function getValueAttribute($value)
    {
        if ($this->type === 'multi-select' && !empty($value)) {
            return explode(',', $value);
        }

        return $value;
    }
}
