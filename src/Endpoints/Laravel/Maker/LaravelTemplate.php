<?php

namespace PHPFileManipulator\Endpoints\Laravel\Maker;

use PHPFileManipulator\Endpoints\PHP\Maker\PHPTemplate;

use Illuminate\Support\Str;

class LaravelTemplate extends PHPTemplate
{
    public function get()
    {
        return $this->file->fromString($this->contents())
            ->outputDriver($this->outputDriver());
    }

    protected function outputDriver()
    {
        $outputDriverClass = config('php-file-manipulator.output', \PHPFileManipulator\Drivers\FileOutput::class);
        $outputDriver = new $outputDriverClass;
        $outputDriver->filename = $this->filename();
        $outputDriver->extension = $this->extension();
        $outputDriver->relativeDir = $this->relativeDir();

        return $outputDriver;
    }

    protected function contents()
    {
        $contents = file_get_contents($this->stubPath());

        $contents = $this->replaceLaravelStyleTemplating($contents);
        
        $contents = $this->populate($contents);

        return $contents;
    }

    protected function replaceLaravelStyleTemplating($template)
    {
        // Make {{ keywords }} occurances parsable by replacing them with ___keywords___
        $template = preg_replace_callback(
            '/\{\{\s(\w*)\s\}\}/m',
            function ($matches) {
                return "___$matches[1]___";
            },
            $template
        );

        // Remove empty comment lines
        $template = preg_replace(
            '/\s\/\/$/m',
            '',
            $template
        );
        
        return $template;
    }

    protected function populate($contents)
    {
        return $contents;
    }

    protected function stubPath()
    {
        // HAS PUBLISHED ?

        // ELSE USE DEFAULT
        $name = $this->stub;
        return base_path('vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs/' . $name);
    }
}