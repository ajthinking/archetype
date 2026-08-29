<?php

namespace Archetype\Tests;

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
        $package = dirname(__DIR__);
        $fixtures = __DIR__.'/fixtures/laravel';

        File::ensureDirectoryExists(base_path('vendor/ajthinking/archetype/src/snippets'));
        File::copyDirectory($package.'/src/snippets', base_path('vendor/ajthinking/archetype/src/snippets'));

        // The suite asserts on exact file counts and on the contents of the
        // application skeleton. Testbench ships its own skeleton, and Laravel
        // reshapes it every major version, so replace it wholesale with the
        // pinned fixture app to keep those assertions meaningful.
        File::deleteDirectory(base_path('app'));

        collect(['app', 'database/migrations', 'public'])->each(function ($path) use ($fixtures) {
            File::copyDirectory($fixtures.'/'.$path, base_path($path));
        });
    }

    protected function cleanupDirectories()
    {
        collect([
          Config::get('archetype.roots.debug.root'),
          Config::get('archetype.roots.output.root'),
        ])->filter(function ($directory) {
            // The console tests point the output root at the application
            // itself, and emptying that would take the fixture with it.
            return $directory
                && File::isDirectory($directory)
                && realpath($directory) !== realpath(base_path());
        })->each(function ($directory) {
            File::deleteDirectory($directory);
        });
    }
}
