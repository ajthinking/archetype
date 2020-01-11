<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Commands\DemoCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use App;
use PHPFileManipulator\Factories\PHPFileFactory;
use PHPFileManipulator\Factories\LaravelFileFactory;
use PHPFileManipulator\Commands\ListAPICommand;
Use Illuminate\Support\Str;

class ServiceProvider extends BaseServiceProvider
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
        $this->bootConfig();
        $this->bootRootStorageDisk();
    }

    private function bootRootStorageDisk()
    {
        $this->app['config']["filesystems.disks.root"] = [
            'driver' => 'local',
            'root' => base_path(),
        ];
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

    private function bootConfig()
    {
        $this->publishes([
            __DIR__.'/Config/default_config.php' => config_path('php-file-manipulator.php'),
        ]);
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
}