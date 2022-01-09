<?php

namespace Archetype\Drivers;

use Archetype\Drivers\OutputInterface;
use Illuminate\Support\Str;
use Archetype\Support\PHPFileStorage;
use TypeError;

class FileOutput implements OutputInterface
{
    public $filename;

    public $extension;

    public $relativeDir;

    public $absoluteDir;

    public $root;

    public $storage;

    public function __construct()
    {
        $this->storage = new PHPFileStorage;
        $this->ensureDefaultRootExists();
    }

    public function save($path, $code)
    {
        $this->ensureDefaultRootExists();
        $this->extractPathProperties($path);
		$this->ensureFilenameIsSet();

        $this->storage->put(
            $this->absolutePath(),
            $code
        );
    }

    public function debug($path = null)
    {
        //
    }

    public function absolutePath()
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

    protected function ensureDefaultRootExists()
    {
        $this->root = $this->root ?? config('archetype')['roots']['output'];
    }

    protected function extractPathProperties($path)
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
    
    protected function absoluteDir()
    {
        return collect([
			$this->root['root'],
			$this->relativeDir,
		])->filter()->join("/");
    }

	protected function ensureFilenameIsSet()
	{
		if(!$this->filename) throw new TypeError('Could not find a filename');
	}
}
