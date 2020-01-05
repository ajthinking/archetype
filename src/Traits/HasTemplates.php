<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use Illuminate\Support\Str;

trait HasTemplates
{
    public function fromTemplate($name, $path, $replacementPairs = [])
    {
        $result = $this->fromString($this->getTemplate($name));

        // TODO:
        // given the template type (model, controller, etc)
        // set default path and namespace letting the user do just
        // LaravelFile::controller('BeerController')->save()
        
        return $result;
    }

    private function getTemplate($name)
    {
        // First, see if the user have supplied their own templates (not implemented yet)

        // Otherwise, get default stub from Illuminate
        $laravelStubDir = 'vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs/';
        $fileName = str_replace('_', '.', $name);
        $fileName = Str::kebab($fileName) . '.stub';

        // Return contents
        return file_get_contents(
            base_path($laravelStubDir . $fileName)
        );
    }
}