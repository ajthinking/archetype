<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\ArrayPropertyResource;

class Hidden extends ArrayPropertyResource
{
    protected $aliases = [
        'fillable', 'fillables'
    ];

    public function get()
    {
        return $this->items('hidden');
    }

    public function set($values)
    {
        return $this->setItems('hidden', $values);
    }
}