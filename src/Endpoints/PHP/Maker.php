<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Support\URI;
use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;

class Maker extends EndpointProvider
{
    protected $filename;
    protected $extension = '.php';
    protected $relativeDir = '';

    protected function setupNames($path, $location = 'file_root')
    {
        $uri = URI::make($path);
        
        $path = config('php-file-manipulator.locations.' . $location) . DIRECTORY_SEPARATOR . $uri->path();
        $path = Str::of($uri->path())->ltrim('/')->__toString();

        $this->outputDriver = $this->outputDriver(
            $this->emulatedInputDriver($path)
        );

        $this->namespace = $uri->namespace();
        $this->class = $uri->class();
    }

    public function file($name, $options = [])
    {
        $this->setupNames($name);

        return $this->file->fromString($this->stub('empty.php.stub'))
            ->outputDriver($this->outputDriver);        
    }

    public function class($name, $options = [])
    {
        $this->setupNames($name, 'class_root');

        $contents = Str::of($this->stub('class.php.stub'))
            ->replace(['DummyNamespace', '___NAMESPACE___', '{{ namespace }}'], $this->namespace)
            ->replace(['{{ class }}', '___CLASS___', 'DummyClass'], $this->class)
            ->__toString();

        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver);        
    }

    protected function outputDriver($inputDriver)
    {
        $outputDriverClass = config('php-file-manipulator.output', \PHPFileManipulator\Drivers\FileOutput::class);
        $this->outputDriver = new $outputDriverClass;        
        return $this->outputDriver->setDefaultsFrom($inputDriver);
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

    protected function emulatedInputDriver($path)
    {
        $inputDriverClass = config('php-file-manipulator.input', \PHPFileManipulator\Drivers\FileInput::class);
        $inputDriver = new $inputDriverClass;
        return $inputDriver->readPath($path);
    }
}