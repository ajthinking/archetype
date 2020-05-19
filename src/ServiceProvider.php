<?php

namespace PHPFileManipulator;

use App;
use Config;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
Use Illuminate\Support\Str;
use PHPFileManipulator\Commands\DemoCommand;
use PHPFileManipulator\Commands\ErrorsCommand;
use PHPFileManipulator\Commands\ListAPICommand;
use PHPFileManipulator\Commands\RelationshipsDemo;
use PHPFileManipulator\Factories\LaravelFileFactory;
use PHPFileManipulator\Factories\PHPFileFactory;
use PHPFileManipulator\Traits\AddsLaravelStringsToStrWithMacros;

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
    }

    public function boot()
    {
        $this->bootStrMacros();
        $this->publishConfig();
    }

    protected function registerFacades()
    {
        App::bind('PHPFile', function() {
            return app()->make(PHPFileFactory::class);
        });

        App::bind('LaravelFile', function() {
            return app()->make(LaravelFileFactory::class);
        });
    }    

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/config/php-file-manipulator.php' => config_path('php-file-manipulator.php'),
        ]);
    }

    protected function registerCommands()
    {
        $this->commands([
            ListAPICommand::class,
            DemoCommand::class,
            RelationshipsDemo::class,
            ErrorsCommand::class,
        ]);
    }
}
