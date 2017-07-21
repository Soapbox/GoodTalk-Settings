<?php

namespace SoapBox\Settings\Repositories;

use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use Psr\SimpleCache\CacheInterface;
use SoapBox\Settings\Utilities\Cache;

class Cachesettings implements Settings
{
    private $fetcher;
    private $cache;

    public function __construct(Settings $fetcher, CacheInterface $cache)
    {
        $this->fetcher = $fetcher;
        $this->cache = $cache;
    }

    /**
     * Get the settings for the given group and identifier
     *
     * @param string $group
     * @param string $identifier
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $group, string $identifier): Collection
    {
        return $this->getMultiple($group, new Collection($identifier))->get($identifier);
    }

    /**
     * Get the settings for the given and the identifiers
     *
     * @param string $group
     * @param \Illuminate\Support\Collection $identifiers
     *        A collection of strings
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMultiple(string $group, Collection $identifiers): Collection
    {
        $keys = $identifiers->map(function (string $identifier) use ($group) {
            return Cache::toCacheKey($group, $identifier);
        });

        $cachedValues = (new Collection($this->cache->getMultiple($keys)))
            ->filter()
            ->mapWithKeys(function ($value, $key) {
                return [Cache::cacheKeyToIdentifier($key) => $value];
            });

        $missingIdentifiers = $identifiers->filter(function ($identifier) use ($cachedValues) {
            return !$cachedValues->has($identifier);
        });

        $missingValues = $this->fetcher->getMultiple($group, $missingIdentifiers);

        $this->cache->setMultiple($missingValues->mapWithKeys(function ($value, $key) use ($group) {
            return [Cache::toCacheKey($group, $key) => $value];
        }));

        return $cachedValues->union($missingValues);
    }

    /**
     * Store a setting value override for the given setting
     *
     * @param \SoapBox\Settings\Setting $setting
     *
     * @return \SoapBox\Settings\Setting
     */
    public function store(Setting $setting): Setting
    {
        $this->cache->delete(Cache::toCacheKey($setting->getGroup(), $setting->getIdentifier()));
        return $this->fetcher->store($setting);
    }
}
