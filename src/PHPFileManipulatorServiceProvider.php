<?php

namespace Ajthinking\PHPFileManipulator;

use Illuminate\Support\ServiceProvider;
use App;
use Ajthinking\PHPFileManipulator\Factories\PHPFileFactory;
use Ajthinking\PHPFileManipulator\Factories\LaravelFileFactory;


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