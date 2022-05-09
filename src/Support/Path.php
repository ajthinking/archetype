<?php

namespace Archetype\Support;

use Illuminate\Support\Str;

class Path
{
	public ?string $path;
	public ?string $root;

    final public function __construct($inputPath)
    {
        $this->path = $this->normalize($inputPath);
        $this->root = null;
    }

    public static function make($inputPath)
    {
        return new static($inputPath);
    }

    public function withDefaultRoot($root)
    {
        if (!Str::startsWith($this->path, '/')) {
            $this->root = Str::start($this->normalize($root), '/');
        }

        return $this;
    }

    public function full()
    {
        return $this->root . Str::start($this->path, '/');
    }

    public function relative($root)
    {
        return Str::replaceFirst("$root/", '', $this->full());
    }

    public function __toString()
    {
        return $this->full();
    }

    protected function normalize($filename)
    {
        $isAbsolute = Str::startsWith($filename, '/');
        $path = [];
        foreach (explode('/', $filename) as $part) {
          // ignore parts that have no value
            if (empty($part) || $part === '.') {
                continue;
            }
      
            if ($part !== '..') {
              // cool, we found a new part
                array_push($path, $part);
            } elseif (count($path) > 0) {
              // going back up? sure
                array_pop($path);
            } else {
              // now, here we don't like
                throw new \Exception('Climbing above the root is not permitted.');
            }
        }
      
        return ($isAbsolute ? '/' : '') . join('/', $path);
    }
}
