<?php

namespace PHPFileManipulator\Endpoints\Laravel\Maker;

use PHPFileManipulator\Endpoints\PHP\Maker\PHPTemplate;

use Illuminate\Support\Str;

class LaravelTemplate extends PHPTemplate
{
    public function get()
    {
        $outputDriverClass = config('php-file-manipulator.output', \PHPFileManipulator\Drivers\FileOutput::class);
        $outputDriver = new $outputDriverClass;
        $outputDriver->filename = $this->filename();
        $outputDriver->extension = $this->extension();
        $outputDriver->relativeDir = $this->relativeDir();

        $contents = $this->contents(
            file_get_contents(static::stubPath)
        );

        $file = $this->file->fromString($contents);

        $file->outputDriver($outputDriver);

        return $file;           
    }

    protected function contents($template)
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
}