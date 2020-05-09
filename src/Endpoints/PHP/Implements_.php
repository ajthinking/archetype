<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;

class Implements_ extends EndpointProvider
{
    public function implements($name = null)
    {
        if($this->file->directive('add')) return $this->add($name);

        if($name === null) return $this->get();

        return $this->set($name);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->class()
            ->implements
            ->get()
            ->toArray();
    }

    protected function set($newImplements)
    {
        return $this->file->astQuery()
            ->class()
            ->replaceProperty('implements', $newImplements)
            ->commit()
            ->end()
            ->continue();
    }
    
    protected function add($newImplements)
    {
        return $this->file->astQuery()
            ->class()
            ->replace(function($class) use($newImplements) {
                $class->implements = array_merge($class->implements, $newImplements);
                return $class;
            })
            ->commit()
            ->end()
            ->continue();
    }
}