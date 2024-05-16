<?php

namespace SoapBox\Settings\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use SoapBox\Settings\Models\Mutators\Mutator;
use Database\Factories\SettingDefinitionFactory;
use SoapBox\Settings\Models\Mutators\TextMutator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SoapBox\Settings\Exceptions\InvalidKeyException;
use SoapBox\Settings\Exceptions\InvalidGroupException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SoapBox\Settings\ElequentModelValidation\Validates;
use SoapBox\Settings\ElequentModelValidation\Validatable;

class SettingDefinition extends Model implements Validatable
{
    use Validates, HasFactory;

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
        return self::group($group)->get();
    }

    /**
     * Get the setting definition for the given group and key
     *
     * @throws \SoapBox\Settings\Exceptions\InvalidGroupException
     * @throws \SoapBox\Settings\Exceptions\InvalidKeyException
     *
     * @param string $group
     * @param string $key
     *
     * @return \SoapBox\Settings\Models\SettingDefinition
     */
    public static function getDefinition(string $group, string $key): SettingDefinition
    {
        $definitions = self::getForGroup($group)->keyBy('key');

        if ($definitions->isEmpty()) {
            throw new InvalidGroupException($group);
        }

        if (!$definitions->has($key)) {
            throw new InvalidKeyException($key);
        }

        return $definitions->get($key);
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

    public function getValueMutator(): Mutator
    {
        return new TextMutator();
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

    /**
     * Scope a query to only settings for the given group
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroup(Builder $query, string $value): Builder
    {
        return $query->where('group', $value);
    }

    /**
     * Scope a query to only settings for the given key
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKey(Builder $query, string $value): Builder
    {
        return $query->where('key', $value);
    }

    public static function newFactory()
    {
        return SettingDefinitionFactory::new();
    }
}
