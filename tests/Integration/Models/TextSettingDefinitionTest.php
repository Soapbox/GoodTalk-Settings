<?php

namespace Tests\Integration\Models;

use Tests\TestCase;
use SoapBox\Settings\Models\TextSettingDefinition;

class TextSettingDefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function itSuccessfullyMutatesTheValueOfATextSetting()
    {
        $definition = factory(TextSettingDefinition::class)->create(['value' => 'test_value']);
        $this->assertSame('test_value', $definition->fresh()->value);
    }
}
