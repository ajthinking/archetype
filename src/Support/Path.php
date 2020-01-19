<?php

namespace PHPFileManipulator\Support;

class Path
{
    public function __construct($inputPath, $root = false)
    {
        $this->path = 0;
    }

    public static function make($inputPath, $root = false)
    {
        return new static($inputPath);
    }

    public function __toString()
    {

    }

    protected function getAbsoluteFilename($filename) {
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
      
        // prepend my root directory
        array_unshift($path, $this->getPath());
      
        return join('/', $path);
      }    
}