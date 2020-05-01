<?php

namespace PHPFileManipulator\Traits;

trait HasIntermediateDirectives
{
    public function continue()
    {
        $this->intermediateDirectives = [];
        
        return $this;
    }    

    public function make()
    {
        $this->intermediateDirectives['make'] = true;

        return $this;
    }

    public function add()
    {
        $this->intermediateDirectives['add'] = true;

        return $this;
    }

    public function remove()
    {
        $this->intermediateDirectives['remove'] = true;

        return $this;
    }

    public function empty()
    {
        $this->intermediateDirectives['empty'] = true;

        return $this;
    }    

    public function public()
    {
        $this->intermediateDirectives['public'] = true;
        
        return $this;
    }
    
    public function protected()
    {
        $this->intermediateDirectives['protected'] = true;
        
        return $this;
    }
    
    public function private()
    {
        $this->intermediateDirectives['private'] = true;
        
        return $this;
    }
}