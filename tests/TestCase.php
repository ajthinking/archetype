<?php

namespace Archetype\Tests;

use Archetype\Tests\Support\TestablePHPFileFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->cleanupDirectories();
        $this->setupLaravelDirectories();
		$this->registerTestFacades();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanupDirectories();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('archetype.roots.debug.root', base_path('.debug'));
        $app['config']->set('archetype.roots.output.root', base_path('.output'));
    }

    protected function getPackageProviders($app)
    {
        return [\Archetype\ServiceProvider::class];
    }

	protected function registerTestFacades()
	{
        app()->bind('TestablePHPFile', function () {
            return app()->make(Support\Factories\TestablePHPFileFactory::class);
        });
	}

    protected function setupLaravelDirectories()
    {
        File::ensureDirectoryExists(base_path('vendor/ajthinking/archetype/src/snippets'));
        File::copyDirectory(base_path('./../../../../src/snippets/'), base_path('vendor/ajthinking/archetype/src/snippets'));

        collect(['app', 'database/migrations','public'])->each(function ($path) {
            File::copyDirectory(base_path('./../../../../vendor/laravel/laravel/' . $path), base_path($path));
        });
    }

    protected function cleanupDirectories()
    {
        collect([
          Config::get('archetype.roots.debug.root'),
          Config::get('archetype.roots.output.root'),
        ])->filter(function ($directory) {
            return File::isDirectory($directory);
        })->each(function ($directory) {
            File::deleteDirectory($directory);
        });
    }
}
