<?php

namespace PHPFileManipulator\Endpoints;

use Illuminate\Support\Str;

class BaseTemplate
{
    public static function make(...$args)
    {
        return new static(...$args);
    }

    public function in($file)
    {
        $this->file = $file;
        return $this;
    }

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

    protected function extension()
    {
        return 'php';
    }

    protected function contents($template)
    {
        return $template;
    }
}