<?php

namespace Archetype\Drivers;

use Archetype\Drivers\InputInterface;
use Illuminate\Support\Str;
use Archetype\Support\PHPFileStorage;
use Archetype\Support\URI;

class FileInput implements InputInterface
{
    public ?string $filename = '';

    public ?string $extension = '';

    public ?string $relativeDir = '';

    public ?string $absoluteDir = '';

    public array $root;

    final public function __construct()
    {
        $this->root = config('archetype')['roots']['input'];
    }

    public function readPath($path = null): self
    {
        $this->extractPathProperties($path);
        return $this;
    }

    public function load(string $location = null)
    {
        $this->extractPathProperties($location);

        return (new PHPFileStorage)->get($this->absolutePath());
    }

    public function fileExists($path): bool
    {
        $checker = new static;
        $checker->extractPathProperties($path);
        return is_file($checker->absolutePath());
    }

    public function absolutePath(): string
    {
        return "$this->absoluteDir/$this->filename" . ($this->extension ? ".$this->extension" : "");
    }

    public function filename(): string
    {
        return $this->filename;
    }

    protected function extractPathProperties(string $location): void
    {
		$path = URI::make($location)->path();
		
        $this->filename = $path ? basename($path, '.php') : null;
        
        preg_match('/.*\.(.*)/', basename($path), $matches);
        $this->extension = $matches[1] ?? null;
        
        $pathIsAbsolute = Str::startsWith($path, '/');
        
        if ($pathIsAbsolute) {
            $this->absoluteDir = dirname($path);
        } else {
            $this->absoluteDir = dirname($this->root['root'] . "/" . $path);
        }

        $this->relativeDir = Str::of($this->absoluteDir)
            ->replaceFirst($this->root['root'], '')
            ->ltrim('/')
            ->__toString();
    }
}
