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
    public function itCanCreateATextSetting()
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

    /**
     * @test
     */
    public function itCanUpdateATextSetting()
    {
        $definition = factory(SettingDefinition::class)->states('text')->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->setDefault('new_default');
        });

        $definition = $definition->fresh();

        $this->assertSame('new_default', $definition->value);
    }

    /**
     * @test
     */
    public function itCanUpdateABooleanSetting()
    {
        $definition = factory(SettingDefinition::class)->states('boolean')->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->setDefault(false);
        });

        $definition = $definition->fresh();

        $this->assertSame(false, $definition->value);
    }

    /**
     * @test
     */
    public function itFailsToUpdateASingleSelectSettingWhenItIsInAnInvalidState()
    {
        $this->expectException(ValidationException::class);
        $definition = factory(SettingDefinition::class)->states('single-select')->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->removeOption('option1');
        });
    }

    /**
     * @test
     */
    public function itCanUpdateASingleSelectSetting()
    {
        $definition = factory(SettingDefinition::class)->states('single-select')->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->setDefault('option2');
        });

        $definition = $definition->fresh();

        $this->assertSame('option2', $definition->value);
    }

    /**
     * @test
     */
    public function itFailsToUpdateAMultiSelectSettingWhenItIsInAnInvalidState()
    {
        $this->expectException(ValidationException::class);
        $definition = factory(SettingDefinition::class)->states('multi-select')->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->removeOption('option1');
        });
    }

    /**
     * @test
     */
    public function itCanUpdateAMultiSelectSetting()
    {
        $definition = factory(SettingDefinition::class)->states('multi-select')->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->setDefault(['option2']);
        });

        $definition = $definition->fresh();

        $this->assertSame(['option2'], $definition->value);
    }
}
