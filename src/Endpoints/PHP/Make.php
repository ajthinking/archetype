<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use Archetype\Endpoints\Maker;
use Archetype\Support\URI;
use Exception;
use Illuminate\Support\Str;
use PhpParser\BuilderFactory;

class Make extends Maker
{
    protected string $filename;
    protected string $extension = '.php';
    protected string $relativeDir = '';

	protected $namespace;
	protected $class;
	protected $outputDriver;

    public function file(string $name = 'dummy.php')
    {
        $this->setupNames($name);

        return $this->file->fromString($this->stub('empty.php.stub'))
            ->outputDriver($this->outputDriver);
    }

    public function class(string $name = \App\Dummy::class)
    {
        $this->setupNames($name, 'class_root');

		if(!$this->namespace) {
			throw new Exception('Cannot create a class without a root namespace');
		}

        $contents = Str::of($this->stub('class.php.stub'))
            ->replace(['DummyNamespace', '___NAMESPACE___', '{{ namespace }}'], $this->namespace)
            ->replace(['{{ class }}', '___CLASS___', 'DummyClass'], $this->class)
            ->__toString();

        return $this->file->fromString($contents)
            ->outputDriver($this->outputDriver);
    }


    protected function setupNames(string $path, string $location = 'file_root')
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

    protected function stub(string $name)
    {
        return file_get_contents(
            __DIR__ . "/../../stubs/$name"
        );
    }

    protected function emulatedInputDriver(string $path)
    {
        $inputDriverClass = config('archetype.input', \Archetype\Drivers\FileInput::class);
        $inputDriver = new $inputDriverClass;
        return $inputDriver->readPath($path);
    }
}
