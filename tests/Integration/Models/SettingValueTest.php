<?php

namespace Tests\Integration\Models;

use Tests\TestCase;
use SoapBox\Settings\Models\SettingValue;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Models\BooleanSettingDefinition;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

class SettingValueTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsCreatingATextSettingWhenTheValueDoesNotPassCustomValidation()
    {
        $this->expectException(ValidationException::class);
        $definition = TextSettingDefinition::factory()->create([
            'validation' => 'alpha-dash',
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => 'override.value',
        ]);
    }


    /**
     * @test
     */
    public function itSuccessfullyCreatesATextSettingWhenTheValuePassesCustomValidation()
    {
        $definition = TextSettingDefinition::factory()->create([
            'validation' => 'alpha-dash',
        ]);
        $override = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => 'override-value',
        ]);
        $this->assertSame('override-value', $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itFailsCreatingASingleSelectSettingWhenTheValueIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        $definition = SingleSelectSettingDefinition::factory()->create([
            'options' => ['option1', 'option2'],
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => 'invalid',
        ]);
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenAValueIsNotAnArray()
    {
        $this->expectException(ValidationException::class);
        $definition = MultiSelectSettingDefinition::factory()->create([
            'options' => ['option1', 'option2'],
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => 'option1',
        ]);
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenAValueIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        $definition = MultiSelectSettingDefinition::factory()->create([
            'options' => ['option1', 'option2'],
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => ['option1', 'invalid'],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfATextSetting()
    {
        $definition = TextSettingDefinition::factory()->create();
        $override = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => 'override',
        ]);
        $this->assertSame('override', $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheTrueValueOfABooleanSetting()
    {
        $definition = BooleanSettingDefinition::factory()->create();
        $override = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => true,
        ]);
        $this->assertSame(true, $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheFalseValueOfABooleanSetting()
    {
        $definition = BooleanSettingDefinition::factory()->create();
        $override = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => false,
        ]);
        $this->assertSame(false, $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfASingleSelectSetting()
    {
        $definition = SingleSelectSettingDefinition::factory()->create();
        $override = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => 'option1',
        ]);
        $this->assertSame('option1', $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesASingleValueOfAMultiSelectSetting()
    {
        $definition = MultiSelectSettingDefinition::factory()->create();
        $override = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => ['option1'],
        ]);
        $this->assertSame(['option1'], $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesMultipleValuesOfAMultiSelectSetting()
    {
        $definition = MultiSelectSettingDefinition::factory()->create();
        $override = SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'value' => ['option1', 'option2'],
        ]);
        $this->assertSame(['option1', 'option2'], $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyCreatesASettingValue()
    {
        $definition = TextSettingDefinition::factory()->create();
        $override = SettingValue::create(
            $definition,
            ['value' => 'override', 'identifier' => '1']
        );

        $this->assertSame('1', $override->fresh()->identifier);
        $this->assertSame('override', $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itFailsCreatingASettingValueWhenTheIdentifierContainsADot()
    {
        $this->expectException(ValidationException::class);
        $definition = TextSettingDefinition::factory()->create();
        SettingValue::create(
            $definition,
            ['value' => 'override', 'identifier' => '1.1']
        );
    }
}
