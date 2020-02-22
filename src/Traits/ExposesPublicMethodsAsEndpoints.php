<?php

namespace PHPFileManipulator\Traits;

use BadMethodCallException;
use ReflectionClass;
use ReflectionMethod;

trait ExposesPublicMethodsAsEndpoints
{
    public function supportedEndpointMethods()
    {
        $reflection = new ReflectionClass(static::class);
        return collect($reflection->getMethods())
            ->filter(function($method) {
                if(collect([
                    '__call',
                    '__construct',
                    'canHandle',
                    'getEndpoints',
                    'getHandlerMethod',
                    'supportedEndpointMethods',
                    'aliases',
                ])->contains($method->name)) return false;

                if($method->isPublic()) return true;
            })->values();        
    }

    public function getEndpoints()
    {
        $endpoints = $this->supportedEndpointMethods()
            ->map(function($endpoint) {
                $args = collect($endpoint->getParameters())->map(function($parameter) {
                    return '$' . $parameter->getName();
                })->join(', ');

                return $endpoint->name . "($args)";
        });

        return $endpoints->unique()->toArray();
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

    protected function ownNonReservedPublicMethods()
    {
        return collect($this->ownPublicMethods())
            ->filter(function($method) {
                return !collect($this->reserved_methods)->contains($method);
            })->values();
    }    
}