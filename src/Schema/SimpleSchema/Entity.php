<?php

namespace Archetype\Schema\SimpleSchema;

use Archetype\Schema\SimpleSchema\SimpleSchemaParser;

class Entity
{
    public $name;
    public $attributes;

    public function __construct($name, $attributes)
    {
        $this->name = $name;
        $this->attributes = $attributes;
    }
}