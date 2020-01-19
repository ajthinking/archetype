<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\ArrayPropertyResource;

class Hidden extends ArrayPropertyResource
{
    public function get()
    {
        return $this->items('hidden');
    }

    public function set($values)
    {
        return $this->setItems('hidden', $values);
    }
}