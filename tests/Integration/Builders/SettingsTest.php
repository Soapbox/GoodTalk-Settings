<?php

namespace Tests\Integration\Builders;

use Tests\TestCase;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use SoapBox\Settings\Builders\Settings;
use SoapBox\Settings\Models\SettingValue;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Exceptions\InvalidKeyException;
use SoapBox\Settings\Models\BooleanSettingDefinition;
use SoapBox\Settings\Exceptions\InvalidGroupException;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

class SettingsTest extends TestCase
{
    /**
     * @test
     */
    public function itCanCreateATextSetting()
    {
        Settings::text('settings', 'test', 'value', 'required');
        $definition = SettingDefinition::where('group', 'settings')
            ->where('key', 'test')
            ->firstOrFail();

        $this->assertEquals([], $definition->options);
        $this->assertEquals('value', $definition->value);
        $this->assertEquals('required', $definition->validation);
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
        $definition = TextSettingDefinition::factory()->create();

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
        $definition = BooleanSettingDefinition::factory()->create();

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
        SingleSelectSettingDefinition::factory()->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->removeOption('option1');
        });
    }

    /**
     * @test
     */
    public function itCanUpdateASingleSelectSetting()
    {
        $definition = SingleSelectSettingDefinition::factory()->create();

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
        MultiSelectSettingDefinition::factory()->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->removeOption('option1');
        });
    }

    /**
     * @test
     */
    public function itCanUpdateAMultiSelectSetting()
    {
        $definition = MultiSelectSettingDefinition::factory()->create();

        Settings::update('settings', 'key', function ($updater) {
            $updater->setDefault(['option2']);
        });

        $definition = $definition->fresh();

        $this->assertSame(['option2'], $definition->value);
    }

    /**
     * @test
     */
    public function itFailsSavingTheSettingDefinitionIfTheDefaultNoLongerPassesTheCustomValidation()
    {
        $this->expectException(ValidationException::class);
        TextSettingDefinition::factory()->create(['value' => 'not.valid']);

        Settings::update('settings', 'key', function ($updater) {
            $updater->setValidation('alpha-dash');
        });
    }

    /**
     * @test
     */
    public function itRemovesOverridesThatNoLongerPassCustomValidationForATextSetting()
    {
        $definition = TextSettingDefinition::factory()->create();
        $override1 = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'valid',
        ]);
        $override2 = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '2',
            'value' => 'not-valid',
        ]);

        Settings::update('settings', 'key', function ($updater) {
            $updater->setValidation('alpha');
        });

        $definition = $definition->fresh();

        $this->assertSame('default', $definition->value);
        $this->assertDatabaseHas('setting_values', ['id' => $override1->id]);
        $this->assertDatabaseMissing('setting_values', ['id' => $override2->id]);
    }

    /**
     * @test
     */
    public function itRemovesOverridesThatNoLongerAreInTheSetOfOptionsForASingleSelectSetting()
    {
        $definition = SingleSelectSettingDefinition::factory()->create();
        $override1 = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'option1',
        ]);
        $override2 = SettingValue::factory()->create([
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
        $definition = MultiSelectSettingDefinition::factory()->create();
        $override1 = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => ['option1'],
        ]);
        $override2 = SettingValue::factory()->create([
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

    /**
     * @test
     */
    public function ensuringOverridesThrowsInvalidGroupExceptionWhenTheGroupDoesNotExist()
    {
        $this->expectException(InvalidGroupException::class);
        Settings::ensureHasOverride('invalid_group', 'key', new Collection('1'));
    }

    /**
     * @test
     */
    public function ensuringOverridesThrowsInvalidKeyExceptionWhenTheKeyDoesNotExist()
    {
        $this->expectException(InvalidKeyException::class);
        TextSettingDefinition::factory()->create();
        Settings::ensureHasOverride('settings', 'invalid_key', new Collection('1'));
    }

    /**
     * @test
     */
    public function ensuringOverridesCreatesOverridesWithTheDefaultValue()
    {
        $definition = TextSettingDefinition::factory()->create();
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override',
        ]);

        Settings::ensureHasOverride('settings', 'key', collect(['1', '2', '3']));

        $this->assertDatabaseHas('setting_values', ['identifier' => '1', 'value' => 'override']);
        $this->assertDatabaseHas('setting_values', ['identifier' => '2', 'value' => 'default']);
        $this->assertDatabaseHas('setting_values', ['identifier' => '3', 'value' => 'default']);
    }

    /**
     * @test
     */
    public function itCanDeleteASettingDefinition()
    {
        TextSettingDefinition::factory()->create();
        Settings::delete('settings', 'key');

        $this->assertDatabaseMissing('setting_definitions', ['group' => 'setting_definitions', 'key' => 'key']);
    }

    /**
     * @test
     */
    public function ensureHasOverrideThrowsAnInvalidArgumentExceptionWhenTheGroupHasADot()
    {
        $this->expectException(InvalidArgumentException::class);
        Settings::ensureHasOverride('settings.wat', 'key', collect('1'));
    }

    /**
     * @test
     */
    public function ensureHasOverrideThrowsAnInvalidArgumentExceptionWhenTheKeyHasADot()
    {
        $this->expectException(InvalidArgumentException::class);
        Settings::ensureHasOverride('settings', 'invalid.key', collect('1'));
    }

    /**
     * @test
     */
    public function updateThrowsAnInvalidArgumentExceptionWhenTheGroupHasADot()
    {
        $this->expectException(InvalidArgumentException::class);
        Settings::update('settings.wat', 'key', function () {
        });
    }

    /**
     * @test
     */
    public function updateThrowsAnInvalidArgumentExceptionWhenTheKeyHasADot()
    {
        $this->expectException(InvalidArgumentException::class);
        Settings::update('settings', 'invalid.key', function () {
        });
    }

    /**
     * @test
     */
    public function updateThrowsInvalidGroupExceptionWhenTheGroupDoesNotExist()
    {
        $this->expectException(InvalidGroupException::class);
        Settings::update('invalid_group', 'key', function () {
        });
    }

    /**
     * @test
     */
    public function updateThrowsInvalidKeyExceptionWhenTheKeyDoesNotExist()
    {
        $this->expectException(InvalidKeyException::class);
        TextSettingDefinition::factory()->create();
        Settings::update('settings', 'invalid_key', function () {
        });
    }

    /**
     * @test
     */
    public function deleteThrowsnInvalidArgumentExceptionWhenTheGroupHasADot()
    {
        $this->expectException(InvalidArgumentException::class);
        Settings::delete('settings.wat', 'key');
    }

    /**
     * @test
     */
    public function deleteThrowsnInvalidArgumentExceptionWhenTheKeyHasADot()
    {
        $this->expectException(InvalidArgumentException::class);
        Settings::delete('settings', 'invalid.key');
    }

    /**
     * @test
     */
    public function deleteThrowsInvalidGroupExceptionWhenTheGroupDoesNotExist()
    {
        $this->expectException(InvalidGroupException::class);
        Settings::delete('invalid_group', 'key', collect('1'));
    }

    /**
     * @test
     */
    public function deleteThrowsInvalidKeyExceptionWhenTheKeyDoesNotExist()
    {
        $this->expectException(InvalidKeyException::class);
        TextSettingDefinition::factory()->create();
        Settings::delete('settings', 'invalid_key', collect('1'));
    }
}
