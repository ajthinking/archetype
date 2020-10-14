<?php

namespace Archetype\Schema\SimpleSchema;

use Archetype\Schema\SimpleSchema\SimpleSchemaParser;

class Attribute
{
    public $name;
    public $directives;

    public function __construct($name, $directives)
    {
        $this->name = $name;
        $this->directives = $directives;
    }

    public function hasDirective(string $directive)
    {
        return $this->directives->contains($directive);
    }
}