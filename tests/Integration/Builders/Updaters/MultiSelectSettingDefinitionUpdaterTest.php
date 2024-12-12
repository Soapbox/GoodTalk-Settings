<?php

namespace Tests\Integration\Builders\Updaters;

use Tests\TestCase;
use SoapBox\Settings\Models\MultiSelectSettingDefinition;
use SoapBox\Settings\Builders\Updaters\MultiSelectSettingDefinitionUpdater;

class MultiSelectSettingDefinitionUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateTheDefaultValue()
    {
        $definition = MultiSelectSettingDefinition::factory()->make([
            'options' => ['option1', 'option2'],
            'value' => ['option1'],
        ]);

        $updater = new MultiSelectSettingDefinitionUpdater($definition);
        $updater->setDefault(['option1', 'option2']);

        $this->assertSame(['option1', 'option2'], $definition->value);
    }

    /**
     * @test
     */
    public function itCanAddAnOptionValue()
    {
        $definition = MultiSelectSettingDefinition::factory()->make([
            'options' => ['option1', 'option2'],
            'value' => ['option1'],
        ]);

        $updater = new MultiSelectSettingDefinitionUpdater($definition);
        $updater->addOption('option3');

        $this->assertEquals(['option1', 'option2', 'option3'], $definition->options);
    }

    /**
     * @test
     */
    public function itCanRemoveAnOptionValue()
    {
        $definition = MultiSelectSettingDefinition::factory()->make([
            'options' => ['option1', 'option2'],
            'value' => ['option1'],
        ]);

        $updater = new MultiSelectSettingDefinitionUpdater($definition);
        $updater->removeOption('option2');

        $this->assertEquals(['option1'], $definition->options);
    }

    /**
     * @test
     */
    public function itCanSetTheOptionValues()
    {
        $definition = MultiSelectSettingDefinition::factory()->make([
            'options' => ['option1', 'option2'],
            'value' => ['option1'],
        ]);

        $updater = new MultiSelectSettingDefinitionUpdater($definition);
        $updater->setOptions(['new_option1', 'new_option2']);

        $this->assertEquals(['new_option1', 'new_option2'], $definition->options);
    }
}
