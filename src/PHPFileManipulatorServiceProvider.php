<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\Commands\DemoCommand;
use Illuminate\Support\ServiceProvider;
use App;
use Ajthinking\PHPFileManipulator\Factories\PHPFileFactory;
use Ajthinking\PHPFileManipulator\Factories\LaravelFileFactory;
use Ajthinking\PHPFileManipulator\Commands\ListAPICommand;
Use Illuminate\Support\Str;

class PHPFileManipulatorServiceProvider extends ServiceProvider
{
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
    }
    
    private function registerCommands()
    {
        $this->commands([
            ListAPICommand::class,
            DemoCommand::class,
        ]);
    }

    private function bootStrMacros()
    {
        // Str::hasOneMethodName($target);
        // Str::tableName($target);
        // Str::attributeName($target);

        Str::macro('hasOneMethodName', function ($target) {
            return static::camel(
                collect(explode('\\', $target))->last()
            );
        });
        
        Str::macro('hasManyMethodName', function ($target) {
            return static::camel(
                static::plural(
                    collect(explode('\\', $target))->last()
                )
            );
        });

        Str::macro('belongsToMethodName', function ($target) {
            return static::camel(
                collect(explode('\\', $target))->last()
            );
        });

        Str::macro('belongsToManyMethodName', function ($target) {
            return static::camel(
                static::plural(
                    collect(explode('\\', $target))->last()
                )
            );
        });        

        Str::macro('hasOneDocBlockName', function ($target) {
            return static::studly(
                collect(explode('\\', $target))->last()
            );
        });

        Str::macro('hasManyDocBlockName', function ($target) {
            return static::studly(
                static::plural(
                    collect(explode('\\', $target))->last()
                )
            );
        });        

        Str::macro('belongsToDocBlockName', function ($target) {
            return static::studly(
                collect(explode('\\', $target))->last()
            );
        });
        
        Str::macro('belongsToManyDocBlockName', function ($target) {
            return static::studly(
                static::plural(
                    collect(explode('\\', $target))->last()
                )
            );
        });        
    }

    private function registerFacades()
    {
        App::bind('PHPFile',function() {
            return new PHPFileFactory;
        });

        App::bind('LaravelFile',function() {
            return new LaravelFileFactory;
        });
    }
}