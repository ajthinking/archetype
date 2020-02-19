<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\ArrayPropertyResource;

class Fillable extends ArrayPropertyResource
{   
    public function get()
    {
        $reflection = $this->file->getReflection();
        
        return ($reflection && !$this->file->hasModifications()) ? $this->getWithReflection() : $this->getWithParser();
    }

    protected function getWithReflection()
    {
        return $this->file->getReflection()->getDefaultProperties()['fillable'];
    }

    protected function getWithParser()
    {
        return $this->items('fillable');
    }    

    public function set($values)
    {
        return $this->setItems('fillable', $values);
    }
}