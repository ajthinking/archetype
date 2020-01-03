<?php

namespace Ajthinking\PHPFileManipulator;

use Illuminate\Support\ServiceProvider;
use App;
use Ajthinking\PHPFileManipulator\Factories\PHPFileFactory;
use Ajthinking\PHPFileManipulator\LaravelFile;

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
        $this->registerAliases();
    }

    public function boot()
    {

    }
    
    private function registerFacades()
    {
        App::bind('PHPFile',function() {
            return new PHPFileFactory;
        });

        App::bind('LaravelFile',function() {
            return new LaravelFile;
        });
    }

    private function registerAliases()
    {
        App::alias('PHPFile','Ajthinking\PHPFileManipulator\Facades\PHPFile');
        App::alias('LaravelFile','Ajthinking\PHPFileManipulator\Facades\LaravelFile');
    }
}