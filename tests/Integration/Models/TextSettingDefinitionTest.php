<?php

namespace Tests\Integration\Models;

use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\TextSettingDefinition;

class TextSettingDefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfATextSetting()
    {
        $definition = TextSettingDefinition::factory()->create(['value' => 'test_value']);
        $this->assertSame('test_value', $definition->fresh()->value);
    }

    /**
     * @test
     */
    public function itFailsWhenTheValueDoesNotPassTheCustomValidationRules()
    {
        $this->expectException(ValidationException::class);
        $definition = TextSettingDefinition::factory()->create([
            'value' => 'test_value',
            'validation' => 'integer',
        ]);
    }

    /**
     * @test
     */
    public function itCreatedASettingDefinitionWhenItPassesCustomValidation()
    {
        $definition = TextSettingDefinition::factory()->create([
            'value' => '1',
            'validation' => 'integer',
        ]);

        $this->assertInstanceOf(TextSettingDefinition::class, $definition);
    }
}
