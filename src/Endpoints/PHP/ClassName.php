<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;

class ClassName extends EndpointProvider
{
    /**
     * @example Get file class name
     * @source $file->className()
     *
     * @example Set file class name
     * @source $file->className('MyClass')
     * 
     * @param string $name
     * @return void
     */    
    public function className($name = null)
    {
        if($name === null) return $this->get();

        return $this->set($name);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->class()
            ->name
            ->name
            ->first();
    }    

    protected function set($newClassName)
    {
        return $this->file->astQuery()
            ->class()
            ->name
            ->replaceProperty('name', $newClassName)
            ->commit()
            ->end()
            ->continue();
    }     
}