<?php

namespace Archetype\Schema\SimpleSchema;

use Archetype\Schema\SimpleSchema\SimpleSchema;
use Str;

class SegmentParser
{
    public static function make()
    {
        return new static();
    }

    public function parse($segment)
    {
        
        $segmentRows = Str::of($segment)->split('/' . PHP_EOL . '/');
        $name = $segmentRows->first();
        $attributes = $segmentRows->slice(1)->values()->toArray();

        return new Entity($name, $attributes);
    }
}