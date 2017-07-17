<?php

namespace SoapBox\Settings\Repositories;

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

    public function get(string $group, string $identifier)
    {
        return $this->getMultiple($group, new Collection($identifier))->get($identifier);
    }

    public function getMultiple(string $group, Collection $identifiers)
    {
        $keys = $identifiers->map(function (string $identifier) use ($group) {
            return Cache::toCacheKey($group, $identifier);
        });

        $cachedValues = (new Collection($this->cache->getMultiple($keys)))
            ->filter()
            ->mapWithKeys(function ($value, $key) {
                return [Cache::fromCacheKey($key) => $value];
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
}
