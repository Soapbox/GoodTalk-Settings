<?php

namespace Tests\Units\Fetchers;

use Tests\TestCase;
use Illuminate\Support\Collection;
use SoapBox\Settings\Fetchers\DatabaseFetcher;
use SoapBox\Settings\Models\Eloquent\SettingValue;
use SoapBox\Settings\Models\Eloquent\SettingDefinition;

class DatabaseFetcherTest extends TestCase
{
    /**
     * @test
     */
    public function itRetrieves()
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

        $fetcher = new DatabaseFetcher();
        $result = $fetcher->get('settings', '1');

        $this->assertCount(1, $result);

        $settings = $result->get('1');
        $this->assertCount(2, $settings);
        $this->assertSame('override', $settings->get('setting1')->getValue());
        $this->assertSame('default', $settings->get('setting2')->getValue());
    }
}
