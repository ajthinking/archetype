<?php

namespace Archetype\Drivers;

use Archetype\Drivers\OutputInterface;
use Archetype\Support\PHPFileStorage;
use TypeError;

class FileOutput implements OutputInterface
{
    public string $filename = '';

    public string $extension = '';

    public string $relativeDir = '';

    public string $absoluteDir = '';

    public $root;

    public $storage;

    public function __construct()
    {
        $this->storage = new PHPFileStorage;
        $this->ensureDefaultRootExists();
    }

    public function save(string $path, string $code): void
    {
        $this->ensureDefaultRootExists();
        $this->extractPathProperties($path);
		$this->ensureFilenameIsSet();

        $this->storage->put(
            $this->absolutePath(),
            $code
        );
    }

    public function debug($path = null): void
    {
        //
    }

    public function absolutePath(): string
    {
        return $this->absoluteDir() . "/$this->filename" . ($this->extension ? ".$this->extension" : "");
    }

    public function setDefaultsFrom($inputDriver)
    {
        $this->filename = $inputDriver->filename;
        $this->extension = $inputDriver->extension;
        $this->relativeDir = $inputDriver->relativeDir;

        return $this;
    }

    protected function ensureDefaultRootExists(): void
    {
        $this->root = $this->root ?? config('archetype')['roots']['output'];
    }

    protected function extractPathProperties(string $path): void
    {
        // If no path is supplied, we will rely on default/mirrored input settings
        if (!$path) {
            return;
        }

        preg_match('/(.*)\..*/', basename($path), $matches);
        $this->filename = $path ? basename($path, '.php') : null;
        
        preg_match('/.*\.(.*)/', basename($path), $matches);
        $this->extension = $matches[1] ?? null;
    }
    
    protected function absoluteDir(): string
    {
        return collect([
			$this->root['root'],
			$this->relativeDir,
		])->filter()->join("/");
    }

	protected function ensureFilenameIsSet(): void
	{
		if(!$this->filename) throw new TypeError('Could not find a filename');
	}
}
