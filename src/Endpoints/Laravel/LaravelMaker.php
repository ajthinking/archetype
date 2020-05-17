<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Endpoints\Laravel\Maker\Unimplemented;
use PHPFileManipulator\Endpoints\PHP\Maker;
use PHPFileManipulator\Support\URI\UriFactory;

class LaravelMaker extends Maker
{
    public function command($name, $options = [])
    {
        $this->uri = UriFactory::make($name); // TODO
        $this->filename = $name;
        $this->namespace = 'Some\App\\Namespaze';
        $this->class = $name;
        $command = $options['command'] ?? 'command:name';

        $contents = file_get_contents($this->stubPath('console.stub'));
        $contents = str_replace(['DummyNamespace', '___namespace___', '{{ namespace }}'], $this->namespace, $contents);
        $contents = str_replace(['{{ class }}', 'DummyClass', '___class___'], $this->class, $contents);
        $contents = str_replace(['{{ command }}', 'dummy:command', '___command___'], $command, $contents);                

        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver());        
    }

    public function model($name)
    {
        $this->uri = UriFactory::make($name); // TODO
        $this->filename = $name;
        $this->namespace = 'Some\App\\Namespaze';
        $this->class = $name;

        $contents = file_get_contents($this->stubPath('model.stub'));
        $contents = str_replace(['DummyNamespace', '___namespace___', '{{ namespace }}'], $this->namespace, $contents);
        $contents = str_replace(['{{ class }}', 'DummyClass', '___class___'], $this->class, $contents);                
        dd($contents);
        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver());        
    }    

    protected function stubPath($name)
    {
        return base_path('vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs/' . $name);
    }    
}