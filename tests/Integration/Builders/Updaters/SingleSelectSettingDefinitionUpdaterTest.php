<?php

namespace Tests\Integration\Builders\Updaters;

use Tests\TestCase;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;
use SoapBox\Settings\Builders\Updaters\SingleSelectSettingDefinitionUpdater;

class SingleSelectSettingDefinitionUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateTheDefaultValue()
    {
        $definition = factory(SingleSelectSettingDefinition::class)->make([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);

        $updater = new SingleSelectSettingDefinitionUpdater($definition);
        $updater->setDefault('option2');

        $this->assertSame('option2', $definition->value);
    }

    /**
     * @test
     */
    public function itCanAddAnOptionValue()
    {
        $definition = factory(SingleSelectSettingDefinition::class)->make([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);

        $updater = new SingleSelectSettingDefinitionUpdater($definition);
        $updater->addOption('option3');

        $this->assertEquals(['option1', 'option2', 'option3'], $definition->options);
    }

    /**
     * @test
     */
    public function itCanRemoveAnOptionValue()
    {
        $definition = factory(SingleSelectSettingDefinition::class)->make([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);

        $updater = new SingleSelectSettingDefinitionUpdater($definition);
        $updater->removeOption('option2');

        $this->assertEquals(['option1'], $definition->options);
    }

    /**
     * @test
     */
    public function itCanSetTheOptionValues()
    {
        $definition = factory(SingleSelectSettingDefinition::class)->make([
            'options' => ['option1', 'option2'],
            'value' => 'option1',
        ]);

        $updater = new SingleSelectSettingDefinitionUpdater($definition);
        $updater->setOptions(['new_option1', 'new_option2']);

        $this->assertEquals(['new_option1', 'new_option2'], $definition->options);
    }
}
