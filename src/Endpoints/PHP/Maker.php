<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Support\URI\UriFactory;
use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;

class Maker extends EndpointProvider
{
    protected $filename;
    protected $extension = '.php';
    protected $relativeDir = '';

    protected function setupNames($name)
    {
        $this->uri = UriFactory::make($name);
        $this->filename = $name;
        $this->namespace = 'Some\App\\Namespaze';
        $this->class = $name;        
    }

    public function file($name, $options = [])
    {
        $this->setupNames($name);

        return $this->file->fromString($this->stub('empty.php.stub'))
            ->outputDriver($this->outputDriver());        
    }

    public function class($name, $options = [])
    {
        $this->setupNames($name);

        $contents = Str::of($this->stub('class.php.stub'))
            ->replace(['DummyNamespace', '{{ namespace }}'], $this->namespace)
            ->replace(['{{ class }}', 'DummyClass'], $this->class)
            ->__toString();

        return $this->file->fromString($contents)
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

    protected function filename()
    {
        return $this->filename;
    }

    protected function extension()
    {
        return $this->extension;        
    }

    protected function relativeDir()
    {
        return $this->relativeDir;        
    }

    protected function stub($name)
    {
        return file_get_contents(
            __DIR__ . "/Maker/stubs/$name"
        );
    }
}