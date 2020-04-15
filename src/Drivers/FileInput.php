<?php

namespace PHPFileManipulator\Drivers;

use PHPFileManipulator\Drivers\InputInterface;
use Illuminate\Support\Str;

class FileInput implements InputInterface
{
    public $filename;

    public $extension;

    public $relativeDir;

    public $root;

    public function load($path = null)
    {
        $this->ensureDefaultRootExists();
        $this->extractPathProperties($path);
    }

    protected function ensureDefaultRootExists()
    {
        $this->root = $this->root ?? "SOME DEFAULT ROOT";
    }

    protected function extractPathProperties($path)
    {
        preg_match('/(.*)\..*/', basename($path), $matches);
        $this->filename = $path ? basename($path, '.php') : null;
        
        preg_match('/.*\.(.*)/', basename($path), $matches);
        $this->extension = $matches[1] ?? null;

        $this->relativeDir = dirname($path) ? dirname($path) : null;

        $isAbsolute = Str::startsWith($path, '/');
    }
}