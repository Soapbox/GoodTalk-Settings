<?php

namespace Tests\Integration\Builders;

use Tests\TestCase;
use Illuminate\Support\Collection;
use SoapBox\Settings\Builders\Settings;
use SoapBox\Settings\Models\SettingValue;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Models\BooleanSettingDefinition;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function itFailsSavingTheSettingDefinitionIfTheDefaultNoLongerPassesTheCustomValidation()
    {
        $this->expectException(ValidationException::class);
        $definition = factory(TextSettingDefinition::class)->create(['value' => 'not.valid']);

        Settings::update('settings', 'key', function ($updater) {
            $updater->setValidation('alpha-dash');
        });
    }

    /**
     * @test
     */
    public function itRemovesOverridesThatNoLongerPassCustomValidationForATextSetting()
    {
        $definition = factory(TextSettingDefinition::class)->create();
        $override1 = factory(SettingValue::class)->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'valid',
        ]);
        $override2 = factory(SettingValue::class)->create([
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

    /**
     * @test
     */
    public function ensuringOverridesCreatesOverridesWithTheDefaultValue()
    {
        $definition = factory(TextSettingDefinition::class)->create();
        factory(SettingValue::class)->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override',
        ]);

        Settings::ensureHasOverride('settings', 'key', new Collection(['1', '2', '3']));

        $this->assertDatabaseHas('setting_values', ['identifier' => '1', 'value' => 'override']);
        $this->assertDatabaseHas('setting_values', ['identifier' => '2', 'value' => 'default']);
        $this->assertDatabaseHas('setting_values', ['identifier' => '3', 'value' => 'default']);
    }

    /**
     * @test
     */
    public function itCanDeleteASettingDefinition()
    {
        factory(TextSettingDefinition::class)->create();
        Settings::delete('settings', 'key');

        $this->assertDatabaseMissing('setting_definitions', ['group' => 'setting_definitions', 'key' => 'key']);
    }

    /**
     * @test
     */
    public function itThrowsAModelNotFoundExceptionWhenItCantFindTheDefinitionToDelete()
    {
        $this->expectException(ModelNotFoundException::class);
        Settings::delete('settings', 'key');
    }

    /**
     * @test
     */
    public function ensureHasOverrideThrowsAValidationExceptionWhenTheGroupHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::ensureHasOverride('settings.wat', 'key', new Collection('1'));
    }

    /**
     * @test
     */
    public function ensureHasOverrideThrowsAValidationExceptionWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::ensureHasOverride('settings', 'invalid.key', new Collection('1'));
    }

    /**
     * @test
     */
    public function ensureHasOverrideThrowsAModelNotFoundExceptionWhenThereIsNotSettingForTheKey()
    {
        $this->expectException(ModelNotFoundException::class);
        Settings::ensureHasOverride('settings', 'invalid_key', new Collection('1'));
    }

    /**
     * @test
     */
    public function updateThrowsAValidationExceptionWhenTheGroupHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::update('settings.wat', 'key', function () {
        });
    }

    /**
     * @test
     */
    public function updateThrowsAValidationExceptionWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::update('settings', 'invalid.key', function () {
        });
    }

    /**
     * @test
     */
    public function updateThrowsAModelNotFoundExceptionWhenThereIsNotSettingForTheKey()
    {
        $this->expectException(ModelNotFoundException::class);
        Settings::update('settings', 'invalid_key', function () {
        });
    }

    /**
     * @test
     */
    public function deleteThrowsAValidationExceptionWhenTheGroupHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::delete('settings.wat', 'key');
    }

    /**
     * @test
     */
    public function deleteThrowsAValidationExceptionWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        Settings::delete('settings', 'invalid.key');
    }

    /**
     * @test
     */
    public function deleteThrowsAModelNotFoundExceptionWhenThereIsNotSettingForTheKey()
    {
        $this->expectException(ModelNotFoundException::class);
        Settings::delete('settings', 'invalid_key');
    }
}
