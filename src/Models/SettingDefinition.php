<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use SoapBox\Settings\Models\Handlers\Handler;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jaspaul\EloquentModelValidation\Traits\Validates;
use Jaspaul\EloquentModelValidation\Contracts\Validatable;

class SettingDefinition extends Model implements Validatable
{
    use Validates;

    protected $guarded = [];
    protected $casts = ['options' => 'array'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function getForGroup(string $group): Collection
    {
        return self::where('group', $group)->get();
    }

    public function values(): HasMany
    {
        return $this->hasMany(SettingValue::class);
    }

    private function getHandler(): Handler
    {
        $handler = sprintf('\SoapBox\Settings\Models\Handlers\%sHandler', Str::studly($this->type));
        return new $handler();
    }

    public function getData(): array
    {
        return $this->toArray();
    }

    public function getRules(): array
    {
        $rules = [
            'group' => 'alpha-dash',
            'key' => 'alpha-dash',
            'options' => 'array',
            'options.*' => 'alpha-dash',
        ];

        return array_merge($rules, $this->getHandler()->getRules());
    }

    public function getValueAttribute($value)
    {
        return $this->getHandler()->getValueAttribute($value);
    }

    public function setValueAttribute($value): void
    {
        $this->getHandler()->setValueAttribute($this->attributes, $value);
    }
}
