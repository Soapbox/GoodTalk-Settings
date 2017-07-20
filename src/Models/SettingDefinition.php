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

    /**
     * Get all the setting definitions for the given group
     *
     * @param string $group
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getForGroup(string $group): Collection
    {
        return self::where('group', $group)->get();
    }

    /**
     * Define the relationship to the setting value overrides
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(SettingValue::class);
    }

    /**
     * Get the handler for this type of setting definition
     *
     * @return \SoapBox\Settings\Models\Handlers\Handler
     */
    private function getHandler(): Handler
    {
        $handler = sprintf('\SoapBox\Settings\Models\Handlers\%sHandler', Str::studly($this->type));
        return new $handler();
    }

    /**
     * Get the data to validate
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->toArray();
    }

    /**
     * Get the validation rules for this model
     *
     * @return array
     */
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

    /**
     * Convert the value attribute from the serialized value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        return $this->getHandler()->deserializeValue($value);
    }

    /**
     * Convert the value to the serialized value for the database
     *
     * @param mixed $value
     *
     * @return void
     */
    public function setValueAttribute($value): void
    {
        $this->attributes['value'] = $this->getHandler()->serializeValue($value);
    }
}
