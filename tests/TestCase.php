<?php

namespace HeaderX\JetstreamPassport\Tests;

use HeaderX\JetstreamPassport\JetstreamPassportServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            JetstreamPassportServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        include_once __DIR__.'/../database/migrations/create_laravel-jetstream-passport_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
