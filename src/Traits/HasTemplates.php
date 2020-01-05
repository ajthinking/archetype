<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use Illuminate\Support\Str;

trait HasTemplates
{
    public function fromTemplate($name, $path, $replacementPairs = [])
    {
        $laravelStubDir = 'vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs/';
        $fileName = str_replace('_', '.', $name);
        $fileName = Str::kebab($fileName) . '.stub';

        $code = file_get_contents(
            base_path($laravelStubDir . $fileName)
        );

        return $this->fromString($code);
    }
}