<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Commands\DemoCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use App;
use PHPFileManipulator\Factories\PHPFileFactory;
use PHPFileManipulator\Factories\LaravelFileFactory;
use PHPFileManipulator\Commands\ListAPICommand;
Use Illuminate\Support\Str;
use Config;
use Illuminate\Support\Arr;
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
        $this->bootDevelopmentRootDisks();
    }

    private function isIinProduction()
    {
        return preg_match(
            '/vendor\/ajthinking\/php-file-manipulator/',
            realpath(__DIR__)
        );
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

    private function publishConfig()
    {
        $this->publishes([
            __DIR__.'/config/php-file-manipulator.php' => config_path('php-file-manipulator.php'),
        ]);        
    }
    
    private function bootDevelopmentRootDisks()
    {
        if($this->isIinProduction()) return;

        config([
            'php-file-manipulator.roots.output.root' => __DIR__ . '/../tests/.output',
            'php-file-manipulator.roots.debug.root' => __DIR__ . '/../tests/.debug',
        ]);
    }    
    
    private function registerCommands()
    {
        $this->commands([
            ListAPICommand::class,
            DemoCommand::class,
        ]);
    }
}