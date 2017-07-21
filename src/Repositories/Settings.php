<?php

namespace SoapBox\Settings\Repositories;

use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;

interface Settings
{
    /**
     * Get the settings for the given group and identifier
     *
     * @param string $group
     * @param string $identifier
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $group, string $identifier): Collection;

    /**
     * Get the settings for the given and the identifiers
     *
     * @param string $group
     * @param \Illuminate\Support\Collection $identifiers
     *        A collection of strings
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMultiple(string $group, Collection $identifiers): Collection;

    /**
     * Store a setting value override for the given setting
     *
     * @param \SoapBox\Settings\Setting $setting
     *
     * @return \SoapBox\Settings\Setting
     */
    public function store(Setting $setting): Setting;
}
