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

    public function load(string $group, string $identifier) : void
    {
        $this->loadMultiple($group, new Collection($identifier));
    }

    public function loadMultiple(string $group, Collection $identifiers) : void
    {
        $this->getMultiple($group, $identifiers);
    }

    public function get(string $group, string $identifier) : Collection
    {
        return $this->settings->get($group, $identifier);
    }

    public function getMultiple(string $group, Collection $identifiers) : Collection
    {
        return $this->settings->getMultiple($group, $identifiers);
    }
}
