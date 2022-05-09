<?php

namespace Archetype\Support;

use Illuminate\Support\Str;

class URI
{
    public $input;
    public $path;
    public string $name;

    final public function __construct($input)
    {
        $this->input = $input;

        $this->path = $this->isPath() ? $input : $this->nameToPath($input);
        $this->name = $this->isName() ? $input : $this->pathToName($input);
    }

    public static function make($input)
    {
        return new static($input);
    }

    public function path()
    {
        return $this->path;
    }

    public function name()
    {
        return $this->name;
    }

    public function namespace()
    {
        $this->name = collect(explode('\\', $this->name))->map(function ($part) {
            $map = config('archetype.locations.namespace_map');
            return  $map[$part] ?? $part;
        })->implode('\\');

        return Str::of($this->name)
            ->replaceLast($this->class(), '')
            ->replaceLast('.php', '')
            ->rtrim('\\')->__toString();
    }
    
    public function class()
    {
        return Str::of(class_basename($this->name))->replaceLast('.php', '')->__toString();
    }

    public function isPath()
    {
        // Empty? -> path
        if ($this->input === '') {
            return true;
        }

        // Extension? -> path
        if (Str::endsWith($this->input, '.php')) {
            return true;
        }

        // Forward slash? -> path
        if (Str::contains($this->input, DIRECTORY_SEPARATOR)) {
            return true;
        }

        // Backward slash? -> name
        if (Str::contains($this->input, '\\')) {
            return false;
        }

        // Starts with lowercase? -> path
        if ($this->input[0] === strtolower($this->input[0])) {
            return true;
        }

        // Starts with uppercase? -> name
        if ($this->input[0] === strtoupper($this->input[0])) {
            return false;
        }

        // Default
        return true;
    }

    public function isName()
    {
        return !$this->isPath($this->input);
    }

    protected function nameToPath($input)
    {
        $parts = collect(explode('\\', $input))->map(function ($part) {
            $map = array_flip(config('archetype.locations.namespace_map'));
            return  $map[$part] ?? $part;
        })->toArray();

        $path = implode(DIRECTORY_SEPARATOR, $parts) . '.php';

        return $path;
    }

    protected function pathToName($input)
    {
        $parts = collect(explode('\\', $input))->map(function ($part) {
            $map = array_flip(config('archetype.locations.namespace_map'));
            return  $map[$part] ?? $part;
        })->toArray();

        $parts = explode(DIRECTORY_SEPARATOR, $input);

        $name = implode('\\', $parts);

        return $name;
    }
}
