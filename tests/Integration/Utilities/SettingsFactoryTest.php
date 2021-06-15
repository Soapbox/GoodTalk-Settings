<?php

namespace Tests\Integration\Utilities;

use Tests\TestCase;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingsFactory;

class SettingsFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCanMakeSettingsWithoutOverrides()
    {
        $definitions = collect()->push(SettingDefinition::factory()->make([
            'key' => 'k1',
            'value' => 'v1',
        ]));
        $definitions->push(SettingDefinition::factory()->make([
            'key' => 'k2',
            'value' => 'v2',
        ]));

        $settings = SettingsFactory::make('1', $definitions, collect());

        $this->assertCount(2, $settings);
        $this->assertSame('k1', $settings->get('k1')->getKey());
        $this->assertSame('v1', $settings->get('k1')->getValue());
        $this->assertSame('1', $settings->get('k1')->getIdentifier());
        $this->assertTrue($settings->get('k1')->getIsDefaultValue());
        $this->assertSame('k2', $settings->get('k2')->getKey());
        $this->assertSame('v2', $settings->get('k2')->getValue());
        $this->assertSame('1', $settings->get('k2')->getIdentifier());
        $this->assertTrue($settings->get('k2')->getIsDefaultValue());
    }

    /**
     * @test
     */
    public function itCanMakeSettingsWithSomeOverrides()
    {
        $definitions = collect()->push(SettingDefinition::factory()->create([
            'key' => 'k1',
            'value' => 'v1',
        ]))->push(SettingDefinition::factory()->create([
            'key' => 'k2',
            'value' => 'v2',
        ]));

        $overrides = collect()->push(SettingValue::factory()->create([
            'setting_definition_id' => $definitions->get(0)->id,
            'identifier' => '1',
            'value' => 'o1',
        ]));

        $settings = SettingsFactory::make('1', $definitions, $overrides);

        $this->assertCount(2, $settings);
        $this->assertSame('k1', $settings->get('k1')->getKey());
        $this->assertSame('o1', $settings->get('k1')->getValue());
        $this->assertSame('1', $settings->get('k1')->getIdentifier());
        $this->assertFalse($settings->get('k1')->getIsDefaultValue());
        $this->assertSame('k2', $settings->get('k2')->getKey());
        $this->assertSame('v2', $settings->get('k2')->getValue());
        $this->assertSame('1', $settings->get('k2')->getIdentifier());
        $this->assertTrue($settings->get('k2')->getIsDefaultValue());
    }

    /**
     * @test
     */
    public function itCanMakeSettingsWithOverrides()
    {
        $definitions = collect()->push(SettingDefinition::factory()->create([
            'key' => 'k1',
            'value' => 'v1',
        ]))->push(SettingDefinition::factory()->create([
            'key' => 'k2',
            'value' => 'v2',
        ]));

        $overrides = collect()->push(SettingValue::factory()->create([
            'setting_definition_id' => $definitions->get(0)->id,
            'identifier' => '1',
            'value' => 'o1',
        ]))
        ->push(SettingValue::factory()->create([
            'setting_definition_id' => $definitions->get(1)->id,
            'identifier' => '1',
            'value' => 'o2',
        ]));

        $settings = SettingsFactory::make('1', $definitions, $overrides);

        $this->assertCount(2, $settings);
        $this->assertSame('k1', $settings->get('k1')->getKey());
        $this->assertSame('o1', $settings->get('k1')->getValue());
        $this->assertSame('1', $settings->get('k1')->getIdentifier());
        $this->assertFalse($settings->get('k1')->getIsDefaultValue());
        $this->assertSame('k2', $settings->get('k2')->getKey());
        $this->assertSame('o2', $settings->get('k2')->getValue());
        $this->assertSame('1', $settings->get('k2')->getIdentifier());
        $this->assertFalse($settings->get('k2')->getIsDefaultValue());
    }
}
