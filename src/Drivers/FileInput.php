<?php

namespace PHPFileManipulator\Drivers;

use PHPFileManipulator\Drivers\InputInterface;
use Illuminate\Support\Str;
use PHPFileManipulator\Support\PHPFileStorage;

class FileInput implements InputInterface
{
    public $filename;

    public $extension;

    public $relativeDir;

    public $absoluteDir;

    public $root;

    public function load($path = null)
    {
        $this->ensureDefaultRootExists();
        $this->extractPathProperties($path);

        return (new PHPFileStorage)->get($this->absolutePath());
    }

    public function absolutePath()
    {
        return "$this->absoluteDir/$this->filename" . ($this->extension ? ".$this->extension" : "");
    }

    public function filename()
    {
        return $this->filename;
    }

    protected function ensureDefaultRootExists()
    {
        $this->root = $this->root ?? config('php-file-manipulator')['roots']['input'];
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