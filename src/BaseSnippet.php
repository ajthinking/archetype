<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\PHPFile;
use BadMethodCallException;

abstract class BaseSnippet
{
    public function __construct(PHPFile $file)
    {
        $this->file = $file;      
    }

    const NOT_IMPLEMENTED = 'Method not implemented for this resource';

    public function renderAST()
    {
        throw new BadMethodCallException($this::NOT_IMPLEMENTED);
    }
}