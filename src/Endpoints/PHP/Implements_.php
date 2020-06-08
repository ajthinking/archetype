<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use Arr;

class Implements_ extends EndpointProvider
{
    /**
     * @example Get class implements
     * @source $file->implements()
     * 
     * @example Set class implements
     * @source $file->implements(['InterfaceA', 'InterfaceB'])
     * 
     * @example Add class implements
     * @source $file->add()->implements('InterfaceC')
     * @return mixed
     */    
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
        $newImplements = Arr::wrap($newImplements);
        
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