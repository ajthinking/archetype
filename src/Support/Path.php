<?php

namespace PHPFileManipulator\Support;

use Illuminate\Support\Str;

class Path
{
    public function __construct($inputPath)
    {
        $this->path = Str::start($this->getAbsolute($inputPath), '/');
        $this->root = null;
    }

    public static function make($inputPath)
    {
        return new static($inputPath);
    }

    public function withRoot($root)
    {
        $this->root = $this->getAbsolute($root);

        return $this;
    }

    public function full()
    {
        return $this->root . $this->path;
    }

    public function __toString()
    {
        return $this->root . $this->path;
    }

    protected function getAbsolute($filename) {
        $path = [];
        foreach(explode('/', $filename) as $part) {
          // ignore parts that have no value
          if (empty($part) || $part === '.') continue;
      
          if ($part !== '..') {
            // cool, we found a new part
            array_push($path, $part);
          }
          else if (count($path) > 0) {
            // going back up? sure
            array_pop($path);
          } else {
            // now, here we don't like
            throw new \Exception('Climbing above the root is not permitted.');
          }
        }
      
        return Str::start(join('/', $path), '/');
      }    
}