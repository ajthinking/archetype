<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use Archetype\Support\URI;
use Archetype\PHPFile;
use Illuminate\Support\Str;

class Maker extends EndpointProvider
{
    protected $filename;
    protected $extension = '.php';
    protected $relativeDir = '';

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


    protected function setupNames($path, $location = 'file_root')
    {
        $relativeLocation = URI::make($path);
        
        $relativeRoot = config('archetype.locations.' . $location)
            . DIRECTORY_SEPARATOR . $relativeLocation->path();

        $relativeRoot = Str::of($relativeRoot)->ltrim('/')->__toString();

        $this->outputDriver = $this->outputDriver(
            $this->emulatedInputDriver($relativeRoot)
        );

        $this->namespace = URI::make($relativeRoot)->namespace();

        $this->class = URI::make($relativeRoot)->class();
    }

    protected function outputDriver($inputDriver)
    {
        $outputDriverClass = config('archetype.output', \Archetype\Drivers\FileOutput::class);
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
            __DIR__ . "/../../stubs/$name"
        );
    }

    protected function emulatedInputDriver($path)
    {
        $inputDriverClass = config('archetype.input', \Archetype\Drivers\FileInput::class);
        $inputDriver = new $inputDriverClass;
        return $inputDriver->readPath($path);
    }
}
