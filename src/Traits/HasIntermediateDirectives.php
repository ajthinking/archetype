<?php

namespace PHPFileManipulator\Traits;

trait HasIntermediateDirectives
{
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

    public function continue()
    {
        $this->intermediateDirectives = [];
        
        return $this;
    }
}