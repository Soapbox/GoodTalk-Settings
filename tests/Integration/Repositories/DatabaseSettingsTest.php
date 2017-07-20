<?php

namespace Tests\Integration\Repositories;

use Tests\TestCase;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Repositories\DatabaseSettings;

class DatabaseSettingsTest extends TestCase
{
    /**
     * @test
     */
    public function itRetrieves()
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

        $fetcher = new DatabaseSettings();
        $settings = $fetcher->get('settings', '1');

        $this->assertCount(2, $settings);
        $this->assertSame('override', $settings->get('setting1')->getValue());
        $this->assertSame('default', $settings->get('setting2')->getValue());
    }
}
