<?php

namespace Tests\Integration\Builders\Updaters;

use Tests\TestCase;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Builders\Updaters\TextSettingDefinitionUpdater;

class TextSettingUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateTheDefaultValue()
    {
        $definition = factory(TextSettingDefinition::class)->make([
            'value' => 'default',
        ]);

        $updater = new TextSettingDefinitionUpdater($definition);
        $updater->setDefault('new_default');

        $this->assertSame('new_default', $definition->value);
    }

    /**
     * @test
     */
    public function itCanUpdateTheValidationRules()
    {
        $definition = factory(TextSettingDefinition::class)->make([
            'value' => 'default',
        ]);

        $updater = new TextSettingDefinitionUpdater($definition);
        $updater->setValidation('required');

        $this->assertSame('required', $definition->validation);
    }
}
