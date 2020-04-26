<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\ArrayPropertyResource;
use Illuminate\Support\Arr;

class Hidden extends ArrayPropertyResource
{
    public function get()
    {
        return $this->items('hidden');
    }

    public function set($values)
    {
        $targets = Arr::wrap($values);

        return $this->setItems('hidden', $values);
    }
}