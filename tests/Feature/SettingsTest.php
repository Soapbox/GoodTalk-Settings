<?php

namespace Tests\Feature;

use Tests\TestCase;
use SoapBox\Settings\Settings;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;

class SettingsTest extends TestCase
{
    /**
     * @test
     */
    public function itCanGetAllSettingsWithOverridesForASingleIdentifier()
    {
        $settingDefinition = factory(SettingDefinition::class)->create([
            'group' => 'settings',
            'key' => 'setting1',
            'value' => 'default',
        ]);
        factory(SettingDefinition::class)->create([
            'group' => 'settings',
            'key' => 'setting2',
            'value' => 'default',
        ]);
        $settingDefinition->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => '1',
                'value' => 'override',
            ]));

        $settings = new Settings();
        $result = $settings->get('settings', '1');

        $this->assertCount(2, $result);
        $this->assertSame('override', $result->get('setting1')->getValue());
        $this->assertSame('default', $result->get('setting2')->getValue());
    }

    /**
     * @test
     */
    public function itCanGetAllSettingsWithOverridesForAManyIdentifier()
    {
        $settingDefinition1 = factory(SettingDefinition::class)->create([
            'group' => 'settings',
            'key' => 'setting1',
            'value' => 'default',
        ]);
        $settingDefinition2 = factory(SettingDefinition::class)->create([
            'group' => 'settings',
            'key' => 'setting2',
            'value' => 'default',
        ]);
        $settingDefinition1->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => '1',
                'value' => 'override1',
            ]));
        $settingDefinition2->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => '2',
                'value' => 'override2',
            ]));

        $settings = new Settings();
        $result = $settings->getMany('settings', new Collection(['1', '2', '3']));

        $this->assertCount(3, $result);
        $this->assertSame('override1', $result->get('1')->get('setting1')->getValue());
        $this->assertSame('default', $result->get('1')->get('setting2')->getValue());
        $this->assertSame('default', $result->get('2')->get('setting1')->getValue());
        $this->assertSame('override2', $result->get('2')->get('setting2')->getValue());
        $this->assertSame('default', $result->get('3')->get('setting1')->getValue());
        $this->assertSame('default', $result->get('3')->get('setting2')->getValue());
    }
}
