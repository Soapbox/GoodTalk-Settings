<?php

namespace Tests\Integration\Repositories;

use Tests\TestCase;
use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use SoapBox\Settings\Utilities\Cache;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use Symfony\Component\Cache\Simple\ArrayCache;
use SoapBox\Settings\Repositories\CacheSettings;
use SoapBox\Settings\Repositories\DatabaseSettings;

class CacheSettingsTest extends TestCase
{
    /**
     * @test
     */
    public function itFetchesFromTheDatabaseWhenTheCacheIsEmpty()
    {
        $definition = factory(SettingDefinition::class)->create([
            'key' => 'setting1',
        ]);
        factory(SettingValue::class)->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override',
        ]);
        factory(SettingDefinition::class)->create([
            'key' => 'setting2',
        ]);

        $fetcher = new CacheSettings(new DatabaseSettings(), new ArrayCache());
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
        $settingDefinition1 = factory(SettingDefinition::class)->make([
            'key' => 'setting1',
        ]);

        $cache = new ArrayCache();
        $collection = new Collection();

        $setting = new Setting($settingDefinition1, '1');
        $setting->setValue('cached_value1');
        $collection->put('setting1', $setting);

        $cache->set(Cache::toCacheKey('settings', '1'), $collection);

        $fetcher = new CacheSettings(new DatabaseSettings(), $cache);
        $settings = $fetcher->get('settings', '1');

        $this->assertCount(1, $settings);
        $this->assertSame('cached_value1', $settings->get('setting1')->getValue());
    }
}
