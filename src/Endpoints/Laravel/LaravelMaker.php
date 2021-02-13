<?php

namespace Archetype\Endpoints\Laravel;

use Archetype\Endpoints\EndpointProvider;
use Archetype\Endpoints\Laravel\Maker\Unimplemented;
use Archetype\Endpoints\PHP\Maker;
use Archetype\Support\URI;
use Illuminate\Support\Str;

class LaravelMaker extends Maker
{
    public function command($name, $options = [])
    {
        $this->setupNames($name);
        $this->command = $options['command'] ?? 'command:name';

        $contents = Str::of($this->stub('console.stub'))
            ->replace(['DummyNamespace', '{{ namespace }}'], $this->namespace)
            ->replace(['{{ class }}', 'DummyClass'], $this->class)
            ->replace(['{{ command }}', 'dummy:command'], $this->command)
            ->__toString();

        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver);
    }

    public function model($name)
    {
        
        $this->setupNames($name);
        
        $contents = Str::of($this->stub('model.stub'))
            ->replace(['DummyNamespace', '{{ namespace }}'], $this->namespace)
            ->replace(['{{ class }}', 'DummyClass'], $this->class)
            ->__toString();

        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver);
    }

    public function controller($name)
    {
        $this->setupNames($name);

        $contents = Str::of($this->stub('controller.plain.stub'))
            ->replace(['DummyNamespace', '{{ namespace }}'], $this->namespace)
            ->replace(['{{ rootNamespace }}'], $this->namespace)
            ->replace(['{{ class }}', 'DummyClass'], $this->class)
            ->__toString();

        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver());
    }

    public function migration($name)
    {
        $this->setupNames($name);

        $contents = Str::of($this->stub('migration.stub'))
            ->replace(['DummyNamespace', '{{ namespace }}'], $this->namespace)
            ->replace(['{{ rootNamespace }}'], $this->namespace)
            ->replace(['{{ class }}', 'DummyClass'], $this->class)
            ->__toString();

        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver());
    }
    
    public function factory($name)
    {
        $this->setupNames($name);

        $contents = Str::of($this->stub('factory.stub'))
            ->replace(['{{ rootNamespace }}'], $this->namespace)
            ->__toString();

        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver());
    }

    protected function stub($name)
    {
        $dir = collect([
            'stubs',
            'vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs',
            'vendor/laravel/framework/src/Illuminate/Routing/Console/stubs',
            'vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs',
            'vendor/laravel/framework/src/Illuminate/Database/Migrations/stubs',
            'vendor/laravel/framework/src/Illuminate/Database/Console/Factories/stubs',
        ])->first(function ($dir) use ($name) {
            return is_file(base_path($dir . "/$name"));
        });

        return file_get_contents(
            base_path($dir . "/$name")
        );
    }
}
