<?php

namespace SoapBox\Settings;

use Illuminate\Support\Collection;
use SoapBox\Settings\Repositories\Settings;
use SoapBox\Settings\Utilities\KeyValidator;

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
     * @throws \InvalidArgumentException
     *         When the group or identifier fail to pass validation. The group
     *         and identifier must only contain characters in the set
     *         [a-zA-Z0-9-_].
     *
     * @param string $group
     * @param string $identifier
     *
     * @return void
     */
    public function load(string $group, string $identifier): void
    {
        $this->loadMultiple($group, collect($identifier));
    }

    /**
     * Ensure the setting collections for the given identifiers in the given
     * group are loaded in the cache
     *
     * @throws \InvalidArgumentException
     *         When the group or identifiers fail to pass validation. The group
     *         and identifiers must only contain characters in the set
     *         [a-zA-Z0-9-_].
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
     * @throws \InvalidArgumentException
     *         When the group or identifier fail to pass validation. The group
     *         and identifier must only contain characters in the set
     *         [a-zA-Z0-9-_].
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
        KeyValidator::validate([$group, $identifier]);
        return $this->settings->get($group, $identifier);
    }

    /**
     * Get all settings for the given identifiers in the given group
     *
     * @throws \InvalidArgumentException
     *         When the group or identifiers fail to pass validation. The group
     *         and identifiers must only contain characters in the set
     *         [a-zA-Z0-9-_].
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
        KeyValidator::validate(array_merge([$group], $identifiers->all()));
        return $this->settings->getMultiple($group, $identifiers);
    }

    /**
     * Save the given setting
     *
     * @throws \SoapBox\Settings\Exceptions\InvalidGroupException
     *         When there are no settings defined for the given group
     * @throws \SoapBox\Settings\Exceptions\InvalidKeyException
     *         When there is no setting defined for the given key
     *
     * @param \SoapBox\Settings\Setting $setting
     *
     * @return \SoapBox\Settings\Setting
     */
    public function store(Setting $setting): Setting
    {
        KeyValidator::validate([$setting->getGroup(), $setting->getKey(), $setting->getIdentifier()]);
        return $this->settings->store($setting);
    }
}
