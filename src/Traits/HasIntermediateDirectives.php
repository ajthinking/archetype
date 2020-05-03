<?php

namespace PHPFileManipulator\Traits;

use PHPFileManipulator\Support\Types;

trait HasIntermediateDirectives
{
    public function directives($directives = null)
    {
        if(!$directives) return $this->intermediateDirectives;
        
        $this->intermediateDirectives = $directives;
        
        return $this;
    }

    public function directive($key, $value = Types::NO_VALUE)
    {
        if($value === Types::NO_VALUE) return $this->intermediateDirectives[$key] ?? null;
        
        $this->intermediateDirectives[$key] = $value;
        
        return $this;
    }

    public function continue()
    {
        $this->intermediateDirectives = [];

        return $this;
    }     

    public function add($value = Types::NO_VALUE)
    {
        $this->directive('add', true);

        if($value !== Types::NO_VALUE) $this->directive('addValue', $value);
        
        return $this;
    }

    public function remove()
    {
        return $this->directive('remove', true);
    }

    public function clear()
    {
        return $this->directive('clear', true);
    }    

    public function empty()
    {
        return $this->directive('empty', true);
    }    

    public function public()
    {
        return $this->directive('flag', 'public');
    }
    
    public function protected()
    {
        return $this->directive('flag', 'protected');
    }
    
    public function private()
    {
        return $this->directive('flag', 'private');
    }

    public function static()
    {
        return $this->directive('flag', 'static');
    }
    
    public function assumeType($value)
    {
        return $this->directive('assumeType', $value);
    }
}