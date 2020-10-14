<?php

namespace Archetype\Schema\SimpleSchema;

use Archetype\Schema\SimpleSchema\Entities\ModelEntity;
use Archetype\Schema\SimpleSchema\Entities\UserEntity;
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
        
        $headline = $segmentRows->first();
        
        

        $headlineParts = Str::of($headline)->split('/ /');

        $name = $headlineParts->first();

        

        $directives = $headlineParts->slice(1)->toArray();

        $attributes = $segmentRows->slice(1)->values()->map(function($row) {
            $attributeParts = Str::of($row)->split('/ /');
            $directives = $attributeParts->slice(1)->values();
            $name = $attributeParts->first();            
            return new Attribute($name, $directives);
        });

        if($name == 'User') return new UserEntity($name, $directives, $attributes);

        return new ModelEntity($name, $directives, $attributes);
    }
}