<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\PHPFile;
use BadMethodCallException;
use Illuminate\Support\Str;

abstract class BaseResource
{
    public function __construct(PHPFile $file)
    {
        $this->file = $file;      
    }

    public function getResourceName()
    {
        return Str::replaceLast(
            'Resource', '', collect(explode('\\', static::class))->last()
        );
    }

    public function __call($method, $args)
    {
        $resource = $this->getResourceName();

        // exact matches are getters/setters
        if(preg_match("/^$resource\$/i", $method)) {
            return $args ? $this->set(...$args) : $this->get();
        }
        // adders
        if(preg_match("/^add$resource\$/i", $method)) {
            return $this->add(...$args);
        }
        // removers
        if(preg_match("/^remove$resource\$/i", $method)) {
            return $this->remove(...$args);
        }        

        throw new BadMethodCallException("Resource " . static::class . " could not resolve method " . $method);
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