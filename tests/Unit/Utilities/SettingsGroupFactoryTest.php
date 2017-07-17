<?php

namespace Tests\Unit\Utilities;

use Tests\TestCase;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingsGroupFactory;

class SettingsGroupFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesACollectionsOfSettingsKeyedByTheirIdentifiers()
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
                'identifier' => 'identifier1',
                'value' => 'o1',
            ]))
        )->push($definitions->get(1)
            ->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => 'identifier2',
                'value' => 'o2',
            ]))
        );

        $identifiers = new Collection(['identifier1', 'identifier2']);

        $settingGroup = SettingsGroupFactory::make($identifiers, $definitions, $overrides);

        $this->assertCount(2, $settingGroup);

        $this->assertTrue($settingGroup->has('identifier1'));
        $settings = $settingGroup->get('identifier1');
        $this->assertCount(2, $settings);
        $this->assertSame('k1', $settings->get('k1')->getKey());
        $this->assertSame('o1', $settings->get('k1')->getValue());
        $this->assertSame('identifier1', $settings->get('k1')->getIdentifier());
        $this->assertSame('k2', $settings->get('k2')->getKey());
        $this->assertSame('v2', $settings->get('k2')->getValue());
        $this->assertSame('identifier1', $settings->get('k2')->getIdentifier());

        $this->assertTrue($settingGroup->has('identifier2'));
        $settings = $settingGroup->get('identifier2');
        $this->assertCount(2, $settings);
        $this->assertSame('k1', $settings->get('k1')->getKey());
        $this->assertSame('v1', $settings->get('k1')->getValue());
        $this->assertSame('identifier2', $settings->get('k1')->getIdentifier());
        $this->assertSame('k2', $settings->get('k2')->getKey());
        $this->assertSame('o2', $settings->get('k2')->getValue());
        $this->assertSame('identifier2', $settings->get('k2')->getIdentifier());
    }

    /**
     * @test
     */
    public function itOnlyUsesOverridesForTheProvidedIdentifiers()
    {
        $definitions = (new Collection())->push(factory(SettingDefinition::class)->create([
            'key' => 'k1',
            'value' => 'v1',
        ]));

        $overrides = (new Collection())->push($definitions->get(0)
            ->values()
            ->save(factory(SettingValue::class)->make([
                'identifier' => 'id1',
                'value' => 'o1',
            ]))
        );

        $identifiers = new Collection(['identifier1']);

        $settingGroup = SettingsGroupFactory::make($identifiers, $definitions, $overrides);

        $this->assertCount(1, $settingGroup);

        $this->assertTrue($settingGroup->has('identifier1'));
        $this->assertFalse($settingGroup->has('id1'));
    }
}
