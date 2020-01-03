<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\PHPFile;
use BadMethodCallException;

abstract class BaseResource
{
    public function __construct(PHPFile $file)
    {
        $this->file = $file;      
    }

    const NOT_IMPLEMENTED = 'Method not implemented for this resource';

    public function get()
    {
        throw new BadMethodCallException($this::NOT_IMPLEMENTED);
    }

    public function set($args)
    {
        throw new BadMethodCallException($this::NOT_IMPLEMENTED);
    }
    
    public function add($args)
    {
        throw new BadMethodCallException($this::NOT_IMPLEMENTED);
    }
    
    public function remove($args = null)
    {
        throw new BadMethodCallException($this::NOT_IMPLEMENTED);
    }
    
    public function ast()
    {
        return $this->file->ast();
    }
}