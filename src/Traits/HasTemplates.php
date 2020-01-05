<?php

namespace Ajthinking\PHPFileManipulator\Traits;

trait HasTemplates
{
    public function fromTemplate($name, $path, $replacementPairs = [])
    {
        $paths = [
            'model' => 'vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs/model.stub'
        ];

        $code = file_get_contents(
            base_path($paths[$name])
        );

        return $this->fromString($code);
    }
}