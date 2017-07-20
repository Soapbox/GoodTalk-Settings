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
}
