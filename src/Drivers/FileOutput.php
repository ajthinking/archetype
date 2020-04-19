<?php

namespace PHPFileManipulator\Drivers;

use PHPFileManipulator\Drivers\OutputInterface;
use Illuminate\Support\Str;
use PHPFileManipulator\Support\PHPFileStorage;

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

    public function save($path = null, $code)
    {
        $this->ensureDefaultRootExists();
        $this->extractPathProperties($path);

        // dd(
        //     $this->root['root'],
        //     $this->relativeDir,
        //     $this->absoluteDir(),
        //     $this->filename,
        //     $this->extension,
        //     $this->absolutePath()
        // ); 

        //file_put_contents($this->absolutePath(), $code);

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
    }

    protected function ensureDefaultRootExists()
    {
        $this->root = $this->root ?? config('php-file-manipulator')['roots']['output'];
    }

    protected function extractPathProperties($path)
    {
        // If no path is supplied, we will rely on default/mirrored input settings
        if(!$path) return;

        preg_match('/(.*)\..*/', basename($path), $matches);
        $this->filename = $path ? basename($path, '.php') : null;
        
        preg_match('/.*\.(.*)/', basename($path), $matches);
        $this->extension = $matches[1] ?? null;
        
        $pathIsAbsolute = Str::startsWith($path, '/');
    }
    
    protected function absoluteDir()
    {
        return $this->root['root'] . "/" . $this->relativeDir;
    }
}