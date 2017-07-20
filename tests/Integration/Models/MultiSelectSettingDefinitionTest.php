<?php

namespace Tests\Integration\Models;

use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;

class MultiSelectSettingDefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenAValueIsNotAnArray()
    {
        $this->expectException(ValidationException::class);
        factory(MultiSelectSettingDefinition::class)->create([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenAValueIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        factory(MultiSelectSettingDefinition::class)->create([
            'options' => ['option1', 'option2'],
            'value' => ['option1', 'invalid'],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesASingleValueOfAMultiSelectSetting()
    {
        $definition = factory(MultiSelectSettingDefinition::class)->create([
            'options' => ['test_value'],
            'value' => ['test_value'],
        ]);
        $this->assertSame(['test_value'], $definition->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesMultipleValuesOfAMultiSelectSetting()
    {
        $definition = factory(MultiSelectSettingDefinition::class)->create([
            'options' => ['test_value1', 'test_value2'],
            'value' => ['test_value1', 'test_value2'],
        ]);
        $this->assertSame(['test_value1', 'test_value2'], $definition->fresh()->value);
    }
}
