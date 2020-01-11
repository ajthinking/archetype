<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\ArrayPropertyResource;

class Fillable extends ArrayPropertyResource
{   
    public function get()
    {
        return $this->items('fillable');
    }

    public function set($values)
    {
        return $this->setItems('fillable', $values);
    }
}