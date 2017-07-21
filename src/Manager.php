<?php

namespace SoapBox\Settings;

use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Repositories\Settings;
use SoapBox\Settings\Models\SettingDefinition;

class Manager
{
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Ensure the setting collection for the given identifier in the given group
     * is loaded in the cache
     *
     * @param string $group
     * @param string $identifier
     *
     * @return void
     */
    public function load(string $group, string $identifier): void
    {
        $this->loadMultiple($group, new Collection($identifier));
    }

    /**
     * Ensure the setting collections for the given identifiers in the given
     * group are loaded in the cache
     *
     * @param string $group
     * @param \Illuminate\Support\Collection $identifiers
     *        A collection of string identifiers
     *
     * @return void
     */
    public function loadMultiple(string $group, Collection $identifiers): void
    {
        $this->getMultiple($group, $identifiers);
    }

    /**
     * Get the setting collection for the given identifier in the given group
     *
     * @param string $group
     * @param string $identifier
     *
     * @return \Illuminate\Support\Collection
     *         A collection of \SoapBox\Settings\Setting objects keyed by the
     *         setting key
     */
    public function get(string $group, string $identifier): Collection
    {
        return $this->settings->get($group, $identifier);
    }

    /**
     * Get all settings for the given identifiers in the given group
     *
     * @param string $group
     * @param \Illuminate\Support\Collection $identifiers
     *        A collection of string identifiers
     *
     * @return \Illuminate\Support\Collection
     *         A collection of setting collections keyed by the identifier
     */
    public function getMultiple(string $group, Collection $identifiers): Collection
    {
        return $this->settings->getMultiple($group, $identifiers);
    }

    /**
     * Save the given setting
     *
     * @param \SoapBox\Settings\Setting $setting
     *
     * @return \SoapBox\Settings\Setting
     */
    public function store(Setting $setting): Setting
    {
        return $this->settings->store($setting);
    }
}
