<?php

namespace Tests\Integration;

use Tests\TestCase;
use SoapBox\Settings\Manager;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;

class ManagerTest extends TestCase
{
    /**
     * @test
     */
    public function itCanGetAllSettingsWithOverridesForASingleIdentifier()
    {
        factory(SettingDefinition::class)->create([
            'key' => 'setting1',
            'value' => 'default',
        ])->values()->save(factory(SettingValue::class)->make([
            'identifier' => '1',
            'value' => 'override',
        ]));
        factory(SettingDefinition::class)->create([
            'key' => 'setting2',
            'value' => 'default',
        ]);

        $settings = app(Manager::class);
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
        factory(SettingDefinition::class)->create([
            'key' => 'setting1',
            'value' => 'default',
        ])->values()->save(factory(SettingValue::class)->make([
            'identifier' => '1',
            'value' => 'override1',
        ]));
        factory(SettingDefinition::class)->create([
            'group' => 'settings',
            'key' => 'setting2',
            'value' => 'default',
        ])->values()->save(factory(SettingValue::class)->make([
            'identifier' => '2',
            'value' => 'override2',
        ]));

        $settings = app(Manager::class);
        $result = $settings->getMultiple('settings', new Collection(['1', '2', '3']));

        $this->assertCount(3, $result);
        $this->assertSame('override1', $result->get('1')->get('setting1')->getValue());
        $this->assertSame('default', $result->get('1')->get('setting2')->getValue());
        $this->assertSame('default', $result->get('2')->get('setting1')->getValue());
        $this->assertSame('override2', $result->get('2')->get('setting2')->getValue());
        $this->assertSame('default', $result->get('3')->get('setting1')->getValue());
        $this->assertSame('default', $result->get('3')->get('setting2')->getValue());
    }

    /**
     * @test
     */
    public function callingLoadWarmsUpTheCache()
    {
        $definition = factory(SettingDefinition::class)->create([
            'key' => 'setting1',
            'value' => 'default',
        ]);
        $settings = app(Manager::class);
        $settings->load('settings', '1');

        $definition->value = 'new_value';
        $definition->save();

        $result = $settings->get('settings', '1');

        $this->assertSame('default', $result->get('setting1')->getValue());
    }
}
