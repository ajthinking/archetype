<?php

namespace PHPFileManipulator\Traits;

use BadMethodCallException;
use ReflectionClass;
use ReflectionMethod;

trait ExposesPublicMethodsAsEndpoints
{
    public function getEndpoints()
    {
        return $this->ownNonReservedPublicMethods()->unique()->toArray();
    }

    protected function ownNonReservedPublicMethods()
    {
        return collect($this->ownPublicMethods())
            ->filter(function($method) {
                return !collect($this->reserved_methods)->contains($method);
            })->values();
    }

    protected function ownPublicMethods()
    {
        $reflection = new ReflectionClass(static::class);
        $methods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
            if ($method->class == $reflection->getName())
                 $methods[] = $method->name;
                 
        return $methods;
    }    
}