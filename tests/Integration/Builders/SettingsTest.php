<?php

namespace Tests\Integration\Builders;

use Tests\TestCase;
use SoapBox\Settings\Builders\Settings;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\SettingDefinition;

class SettingsTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsCreatingATestSettingWhenTheGroupHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::text('settings.group', 'test', 'value');
    }

    /**
     * @test
     */
    public function itFailsCreatingATestSettingWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::text('settings', 'test.key', 'value');
    }

    /**
     * @test
     */
    public function itCanCreateATestSetting()
    {
        Settings::text('settings', 'test', 'value');
        $definition = SettingDefinition::where('group', 'settings')
            ->where('key', 'test')
            ->firstOrFail();

        $this->assertEquals([], $definition->options);
        $this->assertEquals('value', $definition->value);
        $this->assertEquals('text', $definition->type);
    }

    /**
     * @test
     */
    public function itFailsCreatingABooleanSettingWhenTheGroupHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::boolean('settings.group', 'test', true);
    }

    /**
     * @test
     */
    public function itFailsCreatingABooleanSettingWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::boolean('settings', 'test.key', true);
    }

    /**
     * @test
     */
    public function itCanCreateABooleanSetting()
    {
        Settings::boolean('settings', 'test', true);
        $definition = SettingDefinition::where('group', 'settings')
            ->where('key', 'test')
            ->firstOrFail();

        $this->assertEquals([], $definition->options);
        $this->assertEquals(true, $definition->value);
        $this->assertEquals('boolean', $definition->type);
    }

    /**
     * @test
     */
    public function itFailsCreatingASingleSelectSettingWhenTheGroupHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::singleSelect('settings.group', 'test', ['option1', 'option2'], 'option1');
    }

    /**
     * @test
     */
    public function itFailsCreatingASingleSelectSettingWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::singleSelect('settings', 'test.key', ['option1', 'option2'], 'option1');
    }

    /**
     * @test
     */
    public function itFailsCreatingASingleSelectSettingWhenAnOptionHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::singleSelect('settings', 'test.key', ['option.1', 'option2'], 'option1');
    }

    /**
     * @test
     */
    public function itFailsCreatingASingleSelectSettingWhenTheDefaultIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        Settings::singleSelect('settings', 'test', ['option1', 'option2'], 'invalid');
    }

    /**
     * @test
     */
    public function itCanCreateASingleSelectSetting()
    {
        Settings::singleSelect('settings', 'test', ['option1', 'option2'], 'option1');
        $definition = SettingDefinition::where('group', 'settings')
            ->where('key', 'test')
            ->firstOrFail();

        $this->assertEquals(['option1', 'option2'], $definition->options);
        $this->assertEquals('option1', $definition->value);
        $this->assertEquals('single-select', $definition->type);
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenTheGroupHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::multiSelect('settings.group', 'test', ['option1', 'option2'], ['option1']);
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::multiSelect('settings', 'test.key', ['option1', 'option2'], ['option1']);
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenAnOptionHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::multiSelect('settings', 'test.key', ['option.1', 'option2'], ['option1']);
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenTheDefaultIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        Settings::multiSelect('settings', 'test', ['option1', 'option2'], ['invalid']);
    }

    /**
     * @test
     */
    public function itCanCreateAMultiSelectSetting()
    {
        Settings::multiSelect('settings', 'test', ['option1', 'option2'], ['option1', 'option2']);
        $definition = SettingDefinition::where('group', 'settings')
            ->where('key', 'test')
            ->firstOrFail();

        $this->assertEquals(['option1', 'option2'], $definition->options);
        $this->assertEquals(['option1', 'option2'], $definition->value);
        $this->assertEquals('multi-select', $definition->type);
    }
}
