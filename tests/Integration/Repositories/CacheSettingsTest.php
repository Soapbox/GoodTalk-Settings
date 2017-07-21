<?php

namespace Tests\Integration\Repositories;

use Tests\TestCase;
use Illuminate\Support\Collection;
use SoapBox\Settings\Utilities\Cache;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingFactory;
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

        $setting = SettingFactory::make('1', $settingDefinition1);
        $setting->setValue('cached_value1');
        $collection->put('setting1', $setting);

        $cache->set(Cache::toCacheKey('settings', '1'), $collection);

        $fetcher = new CacheSettings(new DatabaseSettings(), $cache);
        $settings = $fetcher->get('settings', '1');

        $this->assertCount(1, $settings);
        $this->assertSame('cached_value1', $settings->get('setting1')->getValue());
    }

    /**
     * @test
     */
    public function itDeletesTheSettingsForTheIdentifierWhenStoringASetting()
    {
        $settingDefinition = factory(SettingDefinition::class)->create([
            'key' => 'setting1',
        ]);

        $cache = new ArrayCache();
        $collection = new Collection();
        $setting = SettingFactory::make('2', $settingDefinition);
        $setting->setValue('cached_value');
        $collection->put('setting1', $setting);
        $cache->set(Cache::toCacheKey('settings', '2'), $collection);

        $collection = new Collection();
        $setting = SettingFactory::make('1', $settingDefinition);
        $setting->setValue('cached_value');
        $collection->put('setting1', $setting);
        $cache->set(Cache::toCacheKey('settings', '1'), $collection);

        $fetcher = new CacheSettings(new DatabaseSettings(), $cache);
        $fetcher->store($setting);

        $this->assertFalse($cache->has('settings.1'));
        $this->assertTrue($cache->has('settings.2'));

        $settings = $fetcher->get('settings', '1');

        $this->assertTrue($cache->has('settings.1'));
        $this->assertTrue($cache->has('settings.2'));
        $this->assertSame('cached_value', $settings->get('setting1')->getValue());
    }
}
