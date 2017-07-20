<?php

namespace SoapBox\Settings\Utilities;

use Illuminate\Support\Collection;

class SettingsGroupFactory
{
    /**
     * Create a collection of setting collections keyed by the identifier
     *
     * @param \Illuminate\Support\Collection $identifiers
     *        A collection of strings
     * @param \Illuminate\Support\Collection $definitions
     *        A collection of \SoapBox\Settings\Models\SettingDefinition objects
     * @param \Illuminate\Support\Collection $overrides
     *        A collection of \SoapBox\Settings\Models\SettingValue objects
     *
     * @return \Illuminate\Support\Collection
     *         A collection of \Illuminate\Support\Collection objects
     */
    public static function make(Collection $identifiers, Collection $definitions, Collection $overrides): Collection
    {
        $overrides = $overrides->groupBy('identifier')
            ->filter(function ($group, $identifier) use ($identifiers) {
                return $identifiers->contains($identifier);
            });

        foreach ($identifiers as $identifier) {
            $overrides->put($identifier, $overrides->get($identifier, new Collection()));
        }

        return $overrides->map(function ($overrides, $identifier) use ($definitions) {
            return SettingsFactory::make($identifier, $definitions, $overrides);
        });
    }
}
