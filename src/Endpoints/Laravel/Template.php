<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use Illuminate\Support\Str;
use PHPFileManipulator\Support\EndpointProvider;

class Template extends EndpointProvider
{
    public function getHandlerMethod($signature, $args)
    {
        return $this->file->templates()->contains($signature) ? 'fromTemplate' : false;
    }

    public function __call($method, $args)
    {
        return $this->fromTemplate($method, ...$args);
    }

    public function fromTemplate($name, $path)
    {
        $result = $this->file->fromString($this->getTemplate($name));

        // TODO:
        // given the template type (model, controller, etc)
        // set default path and namespace letting the user do just
        // LaravelFile::controller('BeerController')->save()
        
        return $result;
    }

    private function getTemplate($name)
    {
        // Get default stub from Illuminate
        $laravelStubDir = 'vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs/';
        $fileName = str_replace('_', '.', $name);
        $fileName = Str::kebab($fileName) . '.stub';

        // Make it parsable by replacing by removing blade syntax, ie {{ namespace }} -> ___namespace___
        return preg_replace_callback(
            '/\{\{\s(\w*)\s\}\}/m',
            function ($matches) {
                return "___$matches[1]___";
            },
            file_get_contents(base_path($laravelStubDir . $fileName))
        );
    }    
}