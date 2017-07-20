<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use SoapBox\Settings\Models\Handlers\Handler;
use SoapBox\Settings\Models\Handlers\TextHandler;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jaspaul\EloquentModelValidation\Traits\Validates;
use Jaspaul\EloquentModelValidation\Contracts\Validatable;

class SettingDefinition extends Model implements Validatable
{
    use Validates;

    protected $guarded = [];
    protected $casts = ['options' => 'array'];
    protected $table = 'setting_definitions';

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $attributes['type'] = static::class;
        parent::__construct($attributes);
    }

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
        return $this->hasMany(SettingValue::class, 'setting_definition_id');
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
        return [
            'group' => 'alpha-dash',
            'key' => 'alpha-dash',
            'options' => 'array',
            'options.*' => 'alpha-dash',
        ];
    }

    public function getValueMutator(): Handler
    {
        return new TextHandler();
    }

    /**
     * Convert the value attribute from the serialized value
     *
     * @param string $value
     *
     * @return bool
     */
    public function getValueAttribute(string $value)
    {
        return $this->getValueMutator()->deserializeValue($value);
    }

    /**
     * Convert the value to the serialized value for the database
     *
     * @param bool $value
     *
     * @return void
     */
    public function setValueAttribute($value): void
    {
        $this->attributes['value'] = $this->getValueMutator()->serializeValue($value);
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        $builder = parent::newQuery();

        if (self::class !== static::class) {
            $builder->where('type', static::class);
        }

        return $builder;
    }

    /**
     * Create a new instance of the given model.
     *
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        // This method just provides a convenient way for us to generate fresh model
        // instances of this current model. It is particularly useful during the
        // hydration of new objects via the Eloquent query builder instances.
        if (isset($attributes['type'])) {
            $model = new $attributes['type']((array) $attributes);
        } else {
            $model = new static((array) $attributes);
        }

        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

        return $model;
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param  array  $attributes
     * @param  string|null  $connection
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = $this->newInstance(Arr::only((array) $attributes, ['type']), true);

        $model->setRawAttributes((array) $attributes, true);

        $model->setConnection($connection ?: $this->getConnectionName());

        return $model;
    }
}
