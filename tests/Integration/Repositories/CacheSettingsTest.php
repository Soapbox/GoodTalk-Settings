<?php

namespace Tests\Integration\Repositories;

use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use SoapBox\Settings\Utilities\Cache;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingFactory;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use SoapBox\Settings\Repositories\CacheSettings;
use SoapBox\Settings\Repositories\DatabaseSettings;

class CacheSettingsTest extends TestCase
{
    /**
     * @test
     */
    public function itFetchesFromTheDatabaseWhenTheCacheIsEmpty()
    {
        $definition = SettingDefinition::factory()->create([
            'key' => 'setting1',
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override',
        ]);
        SettingDefinition::factory()->create([
            'key' => 'setting2',
        ]);

        $repository = new CacheSettings(new DatabaseSettings(), new Psr16Cache(new ArrayAdapter()));
        $settings = $repository->get('settings', '1');

        $this->assertCount(2, $settings);
        $this->assertSame('override', $settings->get('setting1')->getValue());
        $this->assertSame('default', $settings->get('setting2')->getValue());
    }

    /**
     * @test
     */
    public function itFetchesFromTheCacheWhenTheCacheContainsTheSettings()
    {
        $settingDefinition1 = SettingDefinition::factory()->make([
            'key' => 'setting1',
        ]);

        $cache = new Psr16Cache(new ArrayAdapter());
        $collection = new Collection();

        $setting = SettingFactory::make('1', $settingDefinition1);
        $setting->setValue('cached_value1');
        $collection->put('setting1', $setting);

        $cache->set(Cache::toCacheKey('settings', '1'), $collection);

        $repository = new CacheSettings(new DatabaseSettings(), $cache);
        $settings = $repository->get('settings', '1');

        $this->assertCount(1, $settings);
        $this->assertSame('cached_value1', $settings->get('setting1')->getValue());
    }

    /**
     * @test
     */
    public function itDeletesTheSettingsForTheIdentifierWhenStoringASetting()
    {
        $settingDefinition = SettingDefinition::factory()->create([
            'key' => 'setting1',
        ]);

        $cache = new Psr16Cache(new ArrayAdapter());
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

        $repository = new CacheSettings(new DatabaseSettings(), $cache);
        $repository->store($setting);

        $this->assertFalse($cache->has('settings.1'));
        $this->assertTrue($cache->has('settings.2'));

        $settings = $repository->get('settings', '1');

        $this->assertTrue($cache->has('settings.1'));
        $this->assertTrue($cache->has('settings.2'));
        $this->assertSame('cached_value', $settings->get('setting1')->getValue());
    }

    /**
     * @test
     */
    public function itDoesNotDoQueriesWhenTheRequestedSettingIsCached()
    {
        $settingDefinition = SettingDefinition::factory()->make([
            'key' => 'setting1',
        ]);

        $cache = new Psr16Cache(new ArrayAdapter());
        $collection = new Collection();

        $setting = SettingFactory::make('1', $settingDefinition);
        $setting->setValue('cached_value1');
        $collection->put('setting1', $setting);

        $cache->set(Cache::toCacheKey('settings', '1'), $collection);

        $repository = new CacheSettings(new DatabaseSettings(), $cache);

        DB::enableQueryLog();
        $settings = $repository->get('settings', '1');
        $this->assertCount(0, DB::getQueryLog());
    }
}
