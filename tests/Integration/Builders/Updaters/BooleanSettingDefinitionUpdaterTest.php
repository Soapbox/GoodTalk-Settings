<?php

namespace Tests\Integration\Builders\Updaters;

use Tests\TestCase;
use SoapBox\Settings\Models\BooleanSettingDefinition;
use SoapBox\Settings\Builders\Updaters\BooleanSettingDefinitionUpdater;

class BooleanSettingDefinitionUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateTheDefaultValue()
    {
        $definition = BooleanSettingDefinition::factory()->make([
            'value' => true,
        ]);

        $updater = new BooleanSettingDefinitionUpdater($definition);
        $updater->setDefault(false);

        $this->assertSame(false, $definition->value);
    }
}
