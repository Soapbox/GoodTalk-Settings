<?php

namespace Tests\Integration\Models;

use Tests\TestCase;
use SoapBox\Settings\Models\SettingValue;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\SettingDefinition;

class SettingValueTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsCreatingASingleSelectSettingWhenTheValueIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->states('single-select')->create([
            'options' => ['option1', 'option2'],
        ])->values()->save(factory(SettingValue::class)->make([
            'value' => 'invalid',
        ]));
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenAValueIsNotAnArray()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->states('multi-select')->create([
            'options' => ['option1', 'option2'],
        ])->values()->save(factory(SettingValue::class)->make([
            'value' => 'option1',
        ]));
    }

    /**
     * @test
     */
    public function itFailsCreatingAMultiSelectSettingWhenAValueIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        factory(SettingDefinition::class)->states('multi-select')->create([
            'options' => ['option1', 'option2'],
        ])->values()->save(factory(SettingValue::class)->make([
            'value' => ['option1', 'invalid'],
        ]));
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfATextSetting()
    {
        $override = factory(SettingDefinition::class)->states('text')->create()->values()
            ->save(factory(SettingValue::class)->make([
                'value' => 'override',
            ]));
        $this->assertSame('override', $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheTrueValueOfABooleanSetting()
    {
        $override = factory(SettingDefinition::class)->states('boolean')->create()->values()
            ->save(factory(SettingValue::class)->make([
                'value' => true,
            ]));
        $this->assertSame(true, $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheFalseValueOfABooleanSetting()
    {
        $override = factory(SettingDefinition::class)->states('boolean')->create()->values()
            ->save(factory(SettingValue::class)->make([
                'value' => false,
            ]));
        $this->assertSame(false, $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfASingleSelectSetting()
    {
        $override = factory(SettingDefinition::class)->states('single-select')->create()->values()
            ->save(factory(SettingValue::class)->make([
                'value' => 'option1',
            ]));
        $this->assertSame('option1', $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesASingleValueOfAMultiSelectSetting()
    {
        $override = factory(SettingDefinition::class)->states('multi-select')->create()->values()
            ->save(factory(SettingValue::class)->make([
                'value' => ['option1'],
            ]));
        $this->assertSame(['option1'], $override->fresh()->value);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesMultipleValuesOfAMultiSelectSetting()
    {
        $override = factory(SettingDefinition::class)->states('multi-select')->create()->values()
            ->save(factory(SettingValue::class)->make([
                'value' => ['option1', 'option2'],
            ]));
        $this->assertSame(['option1', 'option2'], $override->fresh()->value);
    }
}
