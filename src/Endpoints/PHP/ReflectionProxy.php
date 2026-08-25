<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use ReflectionClass;
use Throwable;

class ReflectionProxy extends EndpointProvider
{
    /**
     * @example Get ReflectionClass
     * @source $file->getReflection()
     *
     * @return mixed
     */
    public function getReflection()
    {
        $class = "\\" . $this->file->namespace() ."\\" . $this->file->className();

        try {
            return $class ? new ReflectionClass($class) : null;
        } catch (Throwable $e) {
            // Autoloading the class can fail outright rather than just miss —
            // a missing parent class or trait raises an Error, not an Exception.
            // A file we cannot reflect on is one we skip, never one that kills
            // the whole query.
            return null;
        }
    }
}
