<?php

namespace Tests\Integration\Builders\Updaters;

use Tests\TestCase;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Builders\Updaters\BooleanSettingUpdater;

class BooleanSettingUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateTheDefaultValue()
    {
        $definition = factory(SettingDefinition::class)->states('boolean')->make([
            'value' => true,
        ]);

        $updater = new BooleanSettingUpdater($definition);
        $updater->setDefault(false);

        $this->assertSame(false, $definition->value);
    }
}
