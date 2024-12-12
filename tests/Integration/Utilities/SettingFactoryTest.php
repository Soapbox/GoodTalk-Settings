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
        $definition = SettingDefinition::factory()->make();

        $setting = SettingFactory::make('identifier', $definition);

        $this->assertSame('key', $setting->getKey());
        $this->assertSame('default', $setting->getValue());
        $this->assertSame('identifier', $setting->getIdentifier());
        $this->assertTrue($setting->getIsDefaultValue());
    }

    /**
     * @test
     */
    public function itCanMakeASettingWithAnOverride()
    {
        $definition = SettingDefinition::factory()->create();
        $override = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => 'override',
        ]);

        $setting = SettingFactory::make('identifier', $definition, $override);

        $this->assertSame('key', $setting->getKey());
        $this->assertSame('override', $setting->getValue());
        $this->assertSame('identifier', $setting->getIdentifier());
        $this->assertFalse($setting->getIsDefaultValue());
    }
}
