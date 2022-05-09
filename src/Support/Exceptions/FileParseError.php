<?php

namespace Archetype\Support\Exceptions;

use Exception;

class FileParseError extends Exception
{
	public string $path;
	public $original;

    public function __construct(string $path, $original)
    {
        $this->path = $path;
        $this->original = $original;
        parent::__construct("Could not read $this->path, " . $this->original->getMessage());
    }
}
