<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Endpoints\PHP\Maker\Empty_;
use PHPFileManipulator\Endpoints\PHP\Maker\Class_;

class Maker extends EndpointProvider
{
    public function file($name)
    {
        return Empty_::make($name)->in($this->file)->get();
    }

    public function class($name)
    {
        return Class_::make($name)->in($this->file)->get();
    }
}