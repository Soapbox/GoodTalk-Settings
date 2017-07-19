<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
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

    public function getData(): array
    {
        return $this->toArray();
    }

    public function getRules(): array
    {
        $rules = [
            'group' => 'alpha-dash',
            'key' => 'alpha-dash',
            'options.*' => 'alpha-dash',
        ];

        switch ($this->type) {
            case 'single-select':
                $rules['value'] = 'in_array:options.*';
                break;
            case 'multi-select':
                $rules['value.*'] = 'in_array:options.*';
                break;
        }

        return $rules;
    }

    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param  string  $key
     * @param  array|string|null  $types
     * @return bool
     */
    public function hasCast($key, $types = null)
    {
        if (array_key_exists('type', $this->attributes)) {
            switch ($this->attributes['type']) {
                case 'text':
                    $this->casts['value'] = 'string';
                    break;
                case 'boolean':
                    $this->casts['value'] = 'boolean';
                    break;
                case 'single-select':
                    $this->casts['value'] = 'string';
                    break;
                case 'multi-select':
                    $this->casts['value'] = 'array';
                    break;
            }
        }

        return parent::hasCast($key, $types);
    }
}
