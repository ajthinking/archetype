<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\ArrayPropertyResource;

class Fillable extends ArrayPropertyResource
{   
    public function get()
    {
        $reflection = $this->file->getReflection();
        
        return $this->getWithParser();

        // THERE IS A PROBLEM! REFLECTION CANT BE USED AFTER MODIFICATION!
        //return $reflection ? $this->getWithReflection() : $this->getWithParser();
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