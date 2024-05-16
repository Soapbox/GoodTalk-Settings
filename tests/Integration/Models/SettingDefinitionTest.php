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
        SettingDefinition::factory()->create([
            'group' => 'with.dot'
        ]);
    }

    /**
     * @test
     */
    public function itFailsCreatingASettingWhenTheKeyHasADot()
    {
        $this->expectException(ValidationException::class);
        SettingDefinition::factory()->create([
            'key' => 'with.dot'
        ]);
    }

    /**
     * @test
     */
    public function itFailsCreatingASettingWhenAnOptionHasADot()
    {
        $this->expectException(ValidationException::class);
        SettingDefinition::factory()->create([
            'options' => ['with.dot']
        ]);
    }

    /**
     * @test
     */
    public function itFailsCreatingASettingWhenOptionsIsNotAnArray()
    {
        $this->expectException(ValidationException::class);
        SettingDefinition::factory()->create([
            'options' => 'option'
        ]);
    }
}
