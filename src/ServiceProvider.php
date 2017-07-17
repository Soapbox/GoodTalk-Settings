<?php

namespace SoapBox\Settings;

use SoapBox\Settings\Fetchers\CacheFetcher;
use SoapBox\Settings\Fetchers\SettingFetcher;
use SoapBox\Settings\Fetchers\DatabaseFetcher;
use Symfony\Component\Cache\Simple\ArrayCache;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../resources/migrations');

        $this->app->bind(SettingFetcher::class, function ($app) {
            return new CacheFetcher(new DatabaseFetcher(), new ArrayCache());
        });
    }
}
