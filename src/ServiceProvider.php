<?php

namespace Archetype;

use App;
use Config;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;
use Archetype\Commands\DemoCommand;
use Archetype\Commands\ErrorsCommand;
use Archetype\Commands\DocumentationCommand;
use Archetype\Commands\ListAPICommand;
use Archetype\Commands\RelationshipsDemo;
use Archetype\Factories\LaravelFileFactory;
use Archetype\Schema\LaravelSchema;
use Archetype\Factories\PHPFileFactory;
use Archetype\Factories\ProjectFactory;
use Archetype\Traits\AddsLaravelStringsToStrWithMacros;

class ServiceProvider extends BaseServiceProvider
{
    use AddsLaravelStringsToStrWithMacros;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
        $this->registerCommands();
        $this->mergeConfigFrom(__DIR__.'/config/archetype.php', 'archetype');
    }

    public function boot()
    {
        $this->bootStrMacros();
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

        App::bind('Archetype\Facades\Project', function () {
            return app()->make(ProjectFactory::class);
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
            ListAPICommand::class,
            DemoCommand::class,
            RelationshipsDemo::class,
            ErrorsCommand::class,
            DocumentationCommand::class,
        ]);
    }
}
