<?php

namespace Ajthinking\PHPFileManipulator\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Ajthinking\PHPFileManipulator\PHPFile;
use Ajthinking\PHPFileManipulator\LaravelFile;
use Illuminate\Contracts\Console\Kernel;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function samplePath($name)
    {
        return "tests/FileSamples/$name";
    }

    protected function userFile()
    {
        return PHPFile::load(
            $this->samplePath('app/User.php')
        );        
    }

    protected function laravelUserFile()
    {
        return LaravelFile::load(
            $this->samplePath('app/User.php')
        );        
    }    
    
    protected function routesFile()
    {
        return PHPFile::load(
            $this->samplePath('routes/web.php')
        );        
    }    
}
