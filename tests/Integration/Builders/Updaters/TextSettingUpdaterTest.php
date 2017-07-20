<?php

namespace Tests\Integration\Builders\Updaters;

use Tests\TestCase;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Builders\Updaters\TextSettingUpdater;

class TextSettingUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateTheDefaultValue()
    {
        $definition = factory(SettingDefinition::class)->states('text')->make([
            'value' => 'default',
        ]);

        $updater = new TextSettingUpdater($definition);
        $updater->setDefault('new_default');

        $this->assertSame('new_default', $definition->value);
    }
}
