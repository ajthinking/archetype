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

    protected function getPackageAliases($app)
    {
        return [
          'LaravelFile' => \Archetype\Facades\LaravelFile::class,
          'PHPFile' => \Archetype\Facades\PHPFile::class,
        ];
    }

    protected function getPackageProviders($app)
    {
        return [\Archetype\ServiceProvider::class];
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

	public function assertMultilineArray($name, $output) {
		preg_match("/$name \= (\[[^\;]*)/s", $output, $matches);
		$code = $matches[1];
		$commas = substr_count($code, ',');
		
		$this->assertEquals(
			substr_count($code, PHP_EOL),
			$commas + 1
		);
	}

	public function assertSingleLineEmptyArray($name, $output) {
		$this->assertMatchesRegularExpression("/$name \= (\[\];]*)/s", $output);
	}	
}
