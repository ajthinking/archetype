<?php

namespace Ajthinking\PHPFileManipulator\Resources;

use Ajthinking\PHPFileManipulator\Support\ArrayPropertyResource;

class HiddenResource extends ArrayPropertyResource
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