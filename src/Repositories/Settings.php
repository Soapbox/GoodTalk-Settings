<?php

namespace SoapBox\Settings\Repositories;

use Illuminate\Support\Collection;

interface Settings
{
    public function get(string $group, string $identifier);

    public function getMultiple(string $group, Collection $identifiers);
}
