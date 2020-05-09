<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use ReflectionClass;
use Exception;

class ReflectionProxy extends EndpointProvider
{
    public function getReflection()
    {
        $class = "\\" . $this->file->namespace() ."\\" . $this->file->className();

        try {
            return $class ? new ReflectionClass($class) : null;
        } catch(Exception $e) {
           return null;
        }
    }
}