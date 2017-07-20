<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use SoapBox\Settings\Models\Handlers\Handler;
use SoapBox\Settings\Models\Handlers\TextHandler;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jaspaul\EloquentModelValidation\Traits\Validates;
use Jaspaul\EloquentModelValidation\Contracts\Validatable;

class SettingValue extends Model implements Validatable
{
    use Validates;

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

    public function __construct(array $attributes = [])
    {
        if (isset($attributes['setting_definition_id'])) {
            $this->setting_definition_id = $attributes['setting_definition_id'];
        }

        parent::__construct($attributes);
    }

    public static function create(SettingDefinition $definition, array $attributes)
    {
        $attributes['setting_definition_id'] = $definition->id;
        return parent::create($attributes);
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(SettingDefinition::class, 'setting_definition_id');
    }

    /**
     * Get the handler for this type of setting definition
     *
     * @return \SoapBox\Settings\Models\Handlers\Handler
     */
    private function getHandler(): Handler
    {
        return $this->definition->getValueMutator();
    }

    /**
     * Get the data to validate
     *
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'options' => $this->definition->options,
        ];
        return array_merge($data, $this->toArray());
    }

    /**
     * Get the validation rules for this model
     *
     * @return array
     */
    public function getRules(): array
    {
        return $this->getHandler()->getRules();
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
