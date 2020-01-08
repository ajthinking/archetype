<?php

namespace Ajthinking\PHPFileManipulator\Resources\Laravel;

use Ajthinking\PHPFileManipulator\Resources\ArrayPropertyResource;

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