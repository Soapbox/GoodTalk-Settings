<?php

namespace SoapBox\Settings\Utilities;

class Cache
{
    /**
     * Create a cache key from the given group and identifier
     *
     * @param string $group
     * @param string $identifier
     *
     * @return string
     */
    public static function toCacheKey(string $group, string $identifier): string
    {
        return sprintf('%s.%s', $group, $identifier);
    }

    /**
     * Get the identifier from the given cache key
     *
     * @param string $cacheKey
     *
     * @return string
     */
    public static function cacheKeyToIdentifier(string $cacheKey): string
    {
        return substr($cacheKey, strpos($cacheKey, '.') + 1);
    }
}
