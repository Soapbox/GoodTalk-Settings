<?php

namespace Tests;

use SoapBox\Settings\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../database/factories');
    }

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    protected function refreshApplication()
    {
        parent::refreshApplication();

        if (!Schema::hasTable('migrations')) {
            $this->artisan('migrate');
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
