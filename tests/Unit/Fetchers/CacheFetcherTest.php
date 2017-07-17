<?php

namespace Tests\Units\Fetchers;

use Tests\TestCase;
use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use SoapBox\Settings\Fetchers\CacheFetcher;
use SoapBox\Settings\Fetchers\DatabaseFetcher;
use Symfony\Component\Cache\Simple\ArrayCache;
use SoapBox\Settings\Models\Eloquent\SettingValue;
use SoapBox\Settings\Models\Eloquent\SettingDefinition;

class CacheFetcherTest extends TestCase
{
    /**
     * @test
     */
    public function itFetchesFromTheDatabaseWhenTheCacheIsEmpty()
    {
        $settingDefinition = factory(SettingDefinition::class)->create([
            'key' => 'setting1',
        ]);
        factory(SettingDefinition::class)->create([
            'key' => 'setting2',
        ]);
        $settingDefinition->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => '1',
                'value' => 'override',
            ]));

        $fetcher = new CacheFetcher(new DatabaseFetcher(), new ArrayCache());
        $result = $fetcher->get('settings', '1');

        $this->assertCount(1, $result);

        $settings = $result->get('1');
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
        $settingDefinition2 = factory(SettingDefinition::class)->create([
            'key' => 'setting2',
        ]);

        $cache = new ArrayCache();
        $collection = new Collection();

        $setting = new Setting($settingDefinition1, '1');
        $setting->setValue(factory(SettingValue::class)->make(['value' => 'cached_value1']));
        $collection->put('setting1', $setting);

        $setting = new Setting($settingDefinition2, '1');
        $setting->setValue(factory(SettingValue::class)->make(['value' => 'cached_value2']));
        $collection->put('setting2', $setting);

        $cache->set('settings.1', $collection);

        $fetcher = new CacheFetcher(new DatabaseFetcher(), $cache);
        $result = $fetcher->get('settings', '1');

        $this->assertCount(1, $result);

        $settings = $result->get('1');
        $this->assertCount(2, $settings);
        $this->assertSame('cached_value1', $settings->get('setting1')->getValue());
        $this->assertSame('cached_value2', $settings->get('setting2')->getValue());
    }
}
