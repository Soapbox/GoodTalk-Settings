<?php

namespace Tests\Integration\Utilities;

use Tests\TestCase;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingFactory;

class SettingFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCanMakeASettingWithoutAnOverride()
    {
        $definition = factory(SettingDefinition::class)->make();

        $setting = SettingFactory::make('identifier', $definition);

        $this->assertSame('key', $setting->getKey());
        $this->assertSame('default', $setting->getValue());
        $this->assertSame('identifier', $setting->getIdentifier());
    }

    /**
     * @test
     */
    public function itCanMakeASettingWithAnOverride()
    {
        $definition = factory(SettingDefinition::class)->create();
        $override = factory(SettingValue::class)->create([
            'setting_definition_id' => $definition->id,
            'value' => 'override',
        ]);

        $setting = SettingFactory::make('identifier', $definition, $override);

        $this->assertSame('key', $setting->getKey());
        $this->assertSame('override', $setting->getValue());
        $this->assertSame('identifier', $setting->getIdentifier());
    }
}
