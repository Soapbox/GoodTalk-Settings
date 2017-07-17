<?php

namespace SoapBox\Settings\Utilities;

use Illuminate\Support\Collection;

class SettingsGroupFactory
{
    public static function make(Collection $identifiers, Collection $definitions, Collection $overrides)
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
