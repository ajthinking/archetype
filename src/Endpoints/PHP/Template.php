<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Endpoints\PHP\Template\Empty_;
use PHPFileManipulator\Endpoints\PHP\Template\Class_;

class Template extends EndpointProvider
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