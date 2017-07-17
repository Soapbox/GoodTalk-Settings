<?php

namespace SoapBox\Settings\Utilities;

class Cache
{
    public static function toCacheKey(string $group, string $identifier) : string
    {
        return sprintf('%s.%s', $group, $identifier);
    }

    public static function fromCacheKey(string $cacheKey) : string
    {
        return substr($cacheKey, strpos($cacheKey, '.') + 1);
    }
}
