<?php

namespace Tests\Integration\Models;

use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\SettingDefinition;

class SettingDefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsCreatingASettingWhenTheGroupHasADot()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->create(['group' => 'with.dot']);
    }

    /**
     * @test
     */
    public function itFailsCreatingASettingWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->create(['key' => 'with.dot']);
    }

    /**
     * @test
     */
    public function itFailsCreatingASettingWhenAnOptionHasADot()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->create(['options' => ['with.dot']]);
    }

    /**
     * @test
     */
    public function itFailsCreatingASettingWhenOptionsIsNotAnArray()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->create(['options' => 'option']);
    }

    /**
     * @test
     */
    public function itFailsCreatingASingleSelectSettingWhenTheValueIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->states('single-select')->create([
            'options' => ['option1', 'option2'],
            'value' => 'invalid',
        ]);
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenAValueIsNotAnArray()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->states('multi-select')->create([
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
        factory(SettingDefinition::class)->states('multi-select')->create([
            'options' => ['option1', 'option2'],
            'value' => ['option1', 'invalid'],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfATextSetting()
    {
        $definition = factory(SettingDefinition::class)->states('text')->create(['value' => 'test_value']);
        $this->assertSame('test_value', $definition->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheTrueValueOfABooleanSetting()
    {
        $definition = factory(SettingDefinition::class)->states('boolean')->create(['value' => true]);
        $this->assertSame(true, $definition->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheFalseValueOfABooleanSetting()
    {
        $definition = factory(SettingDefinition::class)->states('boolean')->create(['value' => false]);
        $this->assertSame(false, $definition->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfASingleSelectSetting()
    {
        $definition = factory(SettingDefinition::class)->states('single-select')->create([
            'options' => ['test_value'],
            'value' => 'test_value',
        ]);
        $this->assertSame('test_value', $definition->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesASingleValueOfAMultiSelectSetting()
    {
        $definition = factory(SettingDefinition::class)->states('multi-select')->create([
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
        $definition = factory(SettingDefinition::class)->states('multi-select')->create([
            'options' => ['test_value1', 'test_value2'],
            'value' => ['test_value1', 'test_value2'],
        ]);
        $this->assertSame(['test_value1', 'test_value2'], $definition->fresh()->value);
    }
}
