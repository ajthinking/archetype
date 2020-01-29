<?php

namespace PHPFileManipulator\Traits;

use BadMethodCallException;
use ReflectionClass;

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

        return $endpoints->toArray();
    }  
}