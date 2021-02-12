<?php

namespace Archetype\Support\Exceptions;

use Exception;

class FileParseError extends Exception
{
    public function __construct($path, $original)
    {
        $this->path = $path;
        $this->original = $original;
        parent::__construct("Could not read $this->path, " . $this->original->getMessage());
    }
}
