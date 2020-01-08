<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\Commands\DemoCommand;
use Illuminate\Support\ServiceProvider;
use App;
use Ajthinking\PHPFileManipulator\Factories\PHPFileFactory;
use Ajthinking\PHPFileManipulator\Factories\LaravelFileFactory;
use Ajthinking\PHPFileManipulator\Commands\ListAPICommand;

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

        $this->commands([
            ListAPICommand::class,
            DemoCommand::class,
        ]);
    }

    public function boot()
    {
        //
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