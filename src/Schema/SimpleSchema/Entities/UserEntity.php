<?php

namespace Archetype\Schema\SimpleSchema\Entities;

use Archetype\Schema\SimpleSchema\Entity;

class UserEntity extends Entity
{
    public $name;
    public $directives;
    public $attributes;

    public function __construct($name, $directives, $attributes)
    {
        $this->name = $name;
        $this->directives = $directives;
        $this->attributes = $attributes;
    }
}
