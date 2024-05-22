<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\SettingValueFactory;
use SoapBox\Settings\Models\Mutators\Mutator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SoapBox\Settings\ElequentModelValidation\Validates;
use SoapBox\Settings\ElequentModelValidation\Validatable;

class SettingValue extends Model implements Validatable
{
    use Validates, HasFactory;

    protected $guarded = [];
    protected $with = ['definition'];

    public function __construct(array $attributes = [])
    {
        if (isset($attributes['setting_definition_id'])) {
            $this->setting_definition_id = $attributes['setting_definition_id'];
        }

        parent::__construct($attributes);
    }

    /**
     * Create a new instance of a SettingValue
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @param \SoapBox\Settings\Models\SettingDefinition $definition
     * @param array $attributes
     *
     * @return \SoapBox\Settings\Models\SettingValue
     */
    public static function create(SettingDefinition $definition, array $attributes): SettingValue
    {
        $attributes['setting_definition_id'] = $definition->id;

        $s = new SettingValue($attributes);
        $s->save();

        return $s;
    }

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

    /**
     * The relationship to the setting definition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function definition(): BelongsTo
    {
        return $this->belongsTo(SettingDefinition::class, 'setting_definition_id');
    }

    /**
     * Get the handler for this type of setting definition`
     *
     * @return \SoapBox\Settings\Models\Mutators\Mutator
     */
    private function getMutator(): Mutator
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
        return array_merge($this->definition->getData(), $this->toArray());
    }

    /**
     * Get the validation rules for this model
     *
     * @return array
     */
    public function getRules(): array
    {
        return array_merge($this->definition->getRules(), ['identifier' => 'alpha-dash']);
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
        return $this->getMutator()->deserializeValue($value);
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
        $this->attributes['value'] = $this->getMutator()->serializeValue($value);
    }

    /**
     * Scope a query to only settings for the given group
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $identifier
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIdentifier(Builder $query, string $identifier): Builder
    {
        return $query->where('identifier', $identifier);
    }

    public static function newFactory()
    {
        return SettingValueFactory::new();
    }
}
