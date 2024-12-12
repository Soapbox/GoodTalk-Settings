<?php

namespace Tests\Integration\Models;

use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

class SingleSelectSettingDefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsCreatingASingleSelectSettingWhenTheValueIsNotInTheOptions()
    {
        $this->expectException(ValidationException::class);
        SingleSelectSettingDefinition::factory()->create([
            'options' => ['option1', 'option2'],
            'value' => 'invalid',
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfASingleSelectSetting()
    {
        $definition = SingleSelectSettingDefinition::factory()->create([
            'options' => ['test_value'],
            'value' => 'test_value',
        ]);
        $this->assertSame('test_value', $definition->fresh()->value);
    }
}
