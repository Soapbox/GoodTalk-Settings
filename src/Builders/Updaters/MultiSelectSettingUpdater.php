<?php

namespace SoapBox\Settings\Builders\Updaters;

class MultiSelectSettingUpdater extends SettingUpdater
{
    /**
     * Set the default value for the setting definition
     *
     * @param array $default
     *
     * @return void
     */
    public function setDefault(array $default): void
    {
        $this->definition->value = $default;
    }

    /**
     * Add an option to the set of options for this definition
     *
     * @param string $option
     *
     * @return void
     */
    public function addOption(string $option): void
    {
        $options = $this->definition->options;
        $options[] = $option;
        $this->setOptions($options);
    }

    /**
     * Remove an option from the set of options for this definition
     *
     * @param string $option
     *
     * @return void
     */
    public function removeOption(string $option): void
    {
        $options = $this->definition->options;
        $index = array_search($option, $options);
        unset($options[$index]);
        $this->setOptions($options);
    }

    /**
     * Set the complete set of options for this setting definition
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->definition->options = $options;
    }
}
