<?php

namespace Tests\Integration\Builders\Updaters;

use Tests\TestCase;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Builders\Updaters\SingleSelectSettingUpdater;

class SingleSelectSettingUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateTheDefaultValue()
    {
        $definition = factory(SettingDefinition::class)->states('single-select')->make([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);

        $updater = new SingleSelectSettingUpdater($definition);
        $updater->setDefault('option2');

        $this->assertSame('option2', $definition->value);
    }

    /**
     * @test
     */
    public function itCanAddAnOptionValue()
    {
        $definition = factory(SettingDefinition::class)->states('single-select')->make([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);

        $updater = new SingleSelectSettingUpdater($definition);
        $updater->addOption('option3');

        $this->assertEquals(['option1', 'option2', 'option3'], $definition->options);
    }

    /**
     * @test
     */
    public function itCanRemoveAnOptionValue()
    {
        $definition = factory(SettingDefinition::class)->states('single-select')->make([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);

        $updater = new SingleSelectSettingUpdater($definition);
        $updater->removeOption('option2');

        $this->assertEquals(['option1'], $definition->options);
    }

    /**
     * @test
     */
    public function itCanSetTheOptionValues()
    {
        $definition = factory(SettingDefinition::class)->states('single-select')->make([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);

        $updater = new SingleSelectSettingUpdater($definition);
        $updater->setOptions(['new_option1', 'new_option2']);

        $this->assertEquals(['new_option1', 'new_option2'], $definition->options);
    }
}
