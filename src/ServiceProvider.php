<?php

namespace Archetype;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Archetype\Commands\ErrorsCommand;
use Archetype\Factories\LaravelFileFactory;
use Archetype\Factories\PHPFileFactory;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->registerFacades();
        $this->registerCommands();
        $this->mergeConfigFrom(__DIR__.'/config/archetype.php', 'archetype');
    }

    public function boot()
    {
        $this->publishConfig();
    }

    protected function registerFacades()
    {
        App::bind('PHPFile', function () {
            return app()->make(PHPFileFactory::class);
        });

        App::bind('LaravelFile', function () {
            return app()->make(LaravelFileFactory::class);
        });
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/config/archetype.php' => config_path('archetype.php'),
        ]);
    }

    protected function registerCommands()
    {
        $this->commands([
            ErrorsCommand::class,
        ]);
    }
}
