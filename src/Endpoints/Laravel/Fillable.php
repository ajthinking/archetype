<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\ArrayPropertyResource;
use Illuminate\Support\Arr;

class Fillable extends ArrayPropertyResource
{
    public function get()
    {
        //return $this->basicInformationDriver()->get('fillable');
        return $this->canUseReflection() ? $this->getWithReflection('fillable') : $this->getWithParser('fillable');
    }

    public function set($values)
    {
        $targets = Arr::wrap($values);

        return $this->setItems('fillable', $values);
    }

    public function add($values)
    {
        return $this->setItems(
            'fillable',
            collect($this->get())->concat($values)->toArray()
        );
    }
}