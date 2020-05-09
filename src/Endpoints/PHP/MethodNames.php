<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;

class MethodNames extends EndpointProvider
{
    public function methodNames()
    {
        return $this->get();
    }   

    protected function get()
    {
        return $this->file->astQuery()
            ->method()
            ->name
            ->name
            ->get()
            ->toArray();
    }    
}