<?php

namespace SoapBox\Settings\Fetchers;

use Illuminate\Support\Collection;
use SoapBox\Settings\Caches\Cache;
use Psr\SimpleCache\CacheInterface;

class CacheFetcher implements SettingFetcher
{
    private $fetcher;
    private $cache;

    public function __construct(SettingFetcher $fetcher, CacheInterface $cache)
    {
        $this->fetcher = $fetcher;
        $this->cache = $cache;
    }

    private function toCacheKey(string $group, string $identifier) : string
    {
        return sprintf('%s.%s', $group, $identifier);
    }

    private function fromCacheKey(string $cacheKey) : string
    {
        return substr($cacheKey, strpos($cacheKey, '.') + 1);
    }

    public function get(string $group, string $identifier)
    {
        return $this->getMultiple($group, new Collection($identifier));
    }

    public function getMultiple(string $group, Collection $identifiers)
    {
        $keys = $identifiers->map(function (string $identifier) use ($group) {
            return $this->toCacheKey($group, $identifier);
        });

        $cachedValues = (new Collection($this->cache->getMultiple($keys)))
            ->filter()
            ->mapWithKeys(function ($value, $key) {
                return [$this->fromCacheKey($key) => $value];
            });

        $missingIdentifiers = $identifiers->filter(function ($identifier) use ($cachedValues) {
            return !$cachedValues->has($identifier);
        });

        $missingValues = $this->fetcher->getMultiple($group, $missingIdentifiers);

        $this->cache->setMultiple($missingValues->mapWithKeys(function ($value, $key) use ($group) {
            return [$this->toCacheKey($group, $key) => $value];
        }));

        return $cachedValues->union($missingValues);
    }
}
