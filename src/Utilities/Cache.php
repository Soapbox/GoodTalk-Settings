<?php

namespace SoapBox\Settings\Utilities;

class Cache
{
    /**
     * Create a cache key from the given group and identifier
     */
    public static function toCacheKey(string $group, string $identifier): string
    {
        return sprintf('%s.%s', $group, $identifier);
    }

    /**
     * Get the identifier from the given cache key
     */
    public static function cacheKeyToIdentifier(string $cacheKey): string
    {
        return substr($cacheKey, strpos($cacheKey, '.') + 1);
    }
}
