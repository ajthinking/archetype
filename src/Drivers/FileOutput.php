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
    
    public function save($path = null)
    {
        $this->ensureDefaultRootExists();
        $this->extractPathPropertiess();
    }

    public function debug($path = null)
    {
        //
    }

    protected function ensureDefaultRootExists()
    {
        $this->root = $this->root ?? config('php-file-manipulator')['roots']['output'];
    }

    protected function extractPathProperties($path)
    {
        preg_match('/(.*)\..*/', basename($path), $matches);
        $this->filename = $path ? basename($path, '.php') : null;
        
        preg_match('/.*\.(.*)/', basename($path), $matches);
        $this->extension = $matches[1] ?? null;
        
        $pathIsAbsolute = Str::startsWith($path, '/');

        if($pathIsAbsolute) {
            $this->absoluteDir = dirname($path);
        } else {
            $this->absoluteDir = dirname($this->root['root'] . "/" . $path);
        }

        $this->relativeDir = Str::replaceFirst($this->root['root'] . '/', '', $this->absoluteDir);
    }    
}