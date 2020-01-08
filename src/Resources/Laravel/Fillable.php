<?php

namespace Ajthinking\PHPFileManipulator\Resources\Laravel;

use Ajthinking\PHPFileManipulator\Resources\ArrayPropertyResource;

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