<?php

namespace Archetype\Generators;

use Archetype\Schema\SimpleSchema\SimpleSchema;

abstract class BaseGenerator
{
    public function __construct(SimpleSchema $schema)
    {
        $this->schema = $schema;
    }

    public static function make(SimpleSchema $schema)
    {
        return new static($schema);
    }

    abstract public function build();
}