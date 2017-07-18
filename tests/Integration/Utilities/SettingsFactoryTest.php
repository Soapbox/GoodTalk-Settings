<?php

namespace Tests\Integration\Utilities;

use Tests\TestCase;
use Illuminate\Support\Collection;
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
        $definitions = new Collection();
        $definitions->push(factory(SettingDefinition::class)->make([
            'key' => 'k1',
            'value' => 'v1',
        ]));
        $definitions->push(factory(SettingDefinition::class)->make([
            'key' => 'k2',
            'value' => 'v2',
        ]));

        $settings = SettingsFactory::make('1', $definitions, new Collection());

        $this->assertCount(2, $settings);
        $this->assertSame('k1', $settings->get('k1')->getKey());
        $this->assertSame('v1', $settings->get('k1')->getValue());
        $this->assertSame('1', $settings->get('k1')->getIdentifier());
        $this->assertSame('k2', $settings->get('k2')->getKey());
        $this->assertSame('v2', $settings->get('k2')->getValue());
        $this->assertSame('1', $settings->get('k2')->getIdentifier());
    }

    /**
     * @test
     */
    public function itCanMakeSettingsWithSomeOverrides()
    {
        $definitions = (new Collection())->push(factory(SettingDefinition::class)->create([
            'key' => 'k1',
            'value' => 'v1',
        ]))->push(factory(SettingDefinition::class)->create([
            'key' => 'k2',
            'value' => 'v2',
        ]));

        $overrides = (new Collection())->push($definitions->get(0)
            ->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => '1',
                'value' => 'o1',
            ])));

        $settings = SettingsFactory::make('1', $definitions, $overrides);

        $this->assertCount(2, $settings);
        $this->assertSame('k1', $settings->get('k1')->getKey());
        $this->assertSame('o1', $settings->get('k1')->getValue());
        $this->assertSame('1', $settings->get('k1')->getIdentifier());
        $this->assertSame('k2', $settings->get('k2')->getKey());
        $this->assertSame('v2', $settings->get('k2')->getValue());
        $this->assertSame('1', $settings->get('k2')->getIdentifier());
    }

    /**
     * @test
     */
    public function itCanMakeSettingsWithOverrides()
    {
        $definitions = (new Collection())->push(factory(SettingDefinition::class)->create([
            'key' => 'k1',
            'value' => 'v1',
        ]))->push(factory(SettingDefinition::class)->create([
            'key' => 'k2',
            'value' => 'v2',
        ]));

        $overrides = (new Collection())->push($definitions->get(0)
            ->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => '1',
                'value' => 'o1',
            ])))
            ->push($definitions->get(1)
            ->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => '1',
                'value' => 'o2',
            ])));

        $settings = SettingsFactory::make('1', $definitions, $overrides);

        $this->assertCount(2, $settings);
        $this->assertSame('k1', $settings->get('k1')->getKey());
        $this->assertSame('o1', $settings->get('k1')->getValue());
        $this->assertSame('1', $settings->get('k1')->getIdentifier());
        $this->assertSame('k2', $settings->get('k2')->getKey());
        $this->assertSame('o2', $settings->get('k2')->getValue());
        $this->assertSame('1', $settings->get('k2')->getIdentifier());
    }
}
