<?php

namespace Prezet\DocsTemplate\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Prezet\DocsTemplate\DocsTemplateServiceProvider;
use Prezet\Prezet\PrezetServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            DocsTemplateServiceProvider::class,
            PrezetServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.debug', 'true');

        config()->set('filesystems.disks.prezet', [
            'driver' => 'local',
            'root' => __DIR__.'/../content',
            'throw' => true,
        ]);

        $dbPath = __DIR__.'/Resources/prezet.sqlite';
        touch($dbPath);
        config()->set('database.connections.prezet', [
            'driver' => 'sqlite',
            'database' => $dbPath,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        config()->set('database.default', 'prezet');

        Route::group([], function () {
            require __DIR__.'/../routes/prezet.php';
        });

        $migrations = __DIR__.'/../vendor/prezet/prezet/database/migrations';
        Artisan::call('migrate:fresh', [
            '--path' => $migrations,
            '--database' => 'prezet',
            '--realpath' => true,
            '--no-interaction' => true,
        ]);
    }
}
