<?php

namespace Tests;

use ClassFinder;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use SoapBox\Settings\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        $this->runDatabaseMigrations();
        $this->withFactories(__DIR__ . '/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
