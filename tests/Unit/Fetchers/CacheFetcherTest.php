<?php

namespace Tests\Units\Fetchers;

use Tests\TestCase;
use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use SoapBox\Settings\Utilities\Cache;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Fetchers\CacheFetcher;
use SoapBox\Settings\Fetchers\DatabaseFetcher;
use SoapBox\Settings\Models\SettingDefinition;
use Symfony\Component\Cache\Simple\ArrayCache;

class CacheFetcherTest extends TestCase
{
    /**
     * @test
     */
    public function itFetchesFromTheDatabaseWhenTheCacheIsEmpty()
    {
        factory(SettingDefinition::class)->create([
            'key' => 'setting1',
        ])->values()->save(factory(SettingValue::class)->make([
            'identifier' => '1',
            'value' => 'override',
        ]));
        factory(SettingDefinition::class)->create([
            'key' => 'setting2',
        ]);

        $fetcher = new CacheFetcher(new DatabaseFetcher(), new ArrayCache());
        $settings = $fetcher->get('settings', '1');

        $this->assertCount(2, $settings);
        $this->assertSame('override', $settings->get('setting1')->getValue());
        $this->assertSame('default', $settings->get('setting2')->getValue());
    }

    /**
     * @test
     */
    public function itFetchesFromTheCacheWhenTheCacheContainsTheSettings()
    {
        $settingDefinition1 = factory(SettingDefinition::class)->create([
            'key' => 'setting1',
        ]);

        $cache = new ArrayCache();
        $collection = new Collection();

        $setting = new Setting($settingDefinition1, '1');
        $setting->setValue('cached_value1');
        $collection->put('setting1', $setting);

        $cache->set(Cache::toCacheKey('settings', '1'), $collection);

        $fetcher = new CacheFetcher(new DatabaseFetcher(), $cache);
        $settings = $fetcher->get('settings', '1');

        $this->assertCount(1, $settings);
        $this->assertSame('cached_value1', $settings->get('setting1')->getValue());
    }
}
