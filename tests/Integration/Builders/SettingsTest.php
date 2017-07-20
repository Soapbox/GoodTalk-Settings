<?php

namespace Tests\Integration\Builders;

use Tests\TestCase;
use SoapBox\Settings\Builders\Settings;
use SoapBox\Settings\Models\SettingValue;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Models\BooleanSettingDefinition;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

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
        $this->assertEquals(TextSettingDefinition::class, $definition->type);
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
        $this->assertEquals(BooleanSettingDefinition::class, $definition->type);
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
        $this->assertEquals(SingleSelectSettingDefinition::class, $definition->type);
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
        $this->assertEquals(MultiSelectSettingDefinition::class, $definition->type);
    }

    /**
     * @test
     */
    public function itCanUpdateATextSetting()
    {
        $definition = factory(TextSettingDefinition::class)->create();

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
        $definition = factory(BooleanSettingDefinition::class)->create();

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
        $definition = factory(SingleSelectSettingDefinition::class)->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->removeOption('option1');
        });
    }

    /**
     * @test
     */
    public function itCanUpdateASingleSelectSetting()
    {
        $definition = factory(SingleSelectSettingDefinition::class)->create();

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
        $definition = factory(MultiSelectSettingDefinition::class)->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->removeOption('option1');
        });
    }

    /**
     * @test
     */
    public function itCanUpdateAMultiSelectSetting()
    {
        $definition = factory(MultiSelectSettingDefinition::class)->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->setDefault(['option2']);
        });

        $definition = $definition->fresh();

        $this->assertSame(['option2'], $definition->value);
    }

    /**
     * @test
     */
    public function itRemovesOverridesThatNoLongerAreInTheSetOfOptionsForASingleSelectSetting()
    {
        $definition = factory(SingleSelectSettingDefinition::class)->create();
        $override1 = factory(SettingValue::class)->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'option1',
        ]);
        $override2 = factory(SettingValue::class)->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '2',
            'value' => 'option2',
        ]);

        Settings::update('settings', 'key', function ($updater) {
            $updater->removeOption('option2');
        });

        $definition = $definition->fresh();

        $this->assertSame('option1', $definition->value);
        $this->assertDatabaseHas('setting_values', ['id' => $override1->id]);
        $this->assertDatabaseMissing('setting_values', ['id' => $override2->id]);
    }

    /**
     * @test
     */
    public function itRemovesOverridesThatNoLongerAreInTheSetOfOptionsForAMultiSelectSetting()
    {
        $definition = factory(MultiSelectSettingDefinition::class)->create();
        $override1 = factory(SettingValue::class)->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => ['option1'],
        ]);
        $override2 = factory(SettingValue::class)->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '2',
            'value' => ['option2'],
        ]);

        Settings::update('settings', 'key', function ($updater) {
            $updater->removeOption('option2');
        });

        $definition = $definition->fresh();

        $this->assertSame(['option1'], $definition->value);
        $this->assertDatabaseHas('setting_values', ['id' => $override1->id]);
        $this->assertDatabaseMissing('setting_values', ['id' => $override2->id]);
    }
}
