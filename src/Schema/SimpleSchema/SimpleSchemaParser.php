<?php

namespace Archetype\Schema\SimpleSchema;

use Archetype\Schema\SimpleSchema\SimpleSchema;
use Str;

class SimpleSchemaParser
{
    public $original;
    public $cleaned;
    public $segments = [];
    public $entites = [];

    public static function make()
    {
        return new static();
    }

    public function parse(string $text)
    {
        $this->original = $text;
        $this->cleaned = $this->clean();
        $this->segments = $this->segment();
        $this->entites = $this->entities();

        // ...

        return $this;
    }

    public function get()
    {
        return new SimpleSchema($this->entites);
    }

    protected function clean()
    {
        return (string) Str::of($this->original)
            // force UNIX-style line ending (LF)
            ->replaceMatches('/\r\n/', "\n")
            // remove comments
            ->replaceMatches('/\/\*[\s\S]*?\*\/|([^\\:]|^)\/\/.*$/', "")
            // trim preciding line space
            ->replaceMatches('/^[ \r]+/m', "")
            // trim trailing line space
            ->replaceMatches('/[ \t]+$/m', "")
            // trim preciding newlines
            ->replaceMatches('/^\n+/', "")
            // trim trailing newlines
            ->replaceMatches('/\n+$/', "")
            // remove exessive newlines
            ->replaceMatches('/\n\s+\n/', PHP_EOL . PHP_EOL);
    }

    protected function segment()
    {
        $segments = Str::of($this->cleaned)->split('/\n\s*\n/');

        return $segments->toArray();
    }
    
    protected function entities()
    {
        return collect($this->segments)->map(function($segment) {
            return SegmentParser::make()->parse($segment);
        })->toArray();
    }    
}