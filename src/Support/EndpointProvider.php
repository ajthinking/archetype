<?php

namespace PHPFileManipulator\Support;

use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class EndpointProvider
{
    public function __construct(PHPFile $file = null)
    {
        $this->file = $file;      
    }

    public static function aliases()
    {
        return [
            Str::camel(class_basename(static::class))
        ];
    }    

    public function canHandle($signature, $args)
    {
        return (boolean) $this->getHandlerMethod($signature, $args);
    }

    public function getHandlerMethod($signature, $args)
    {
        return false;
    }

    public function supportedEndpointMethods()
    {
        $reflection = new ReflectionClass(static::class);
        return collect($reflection->getMethods())
            ->filter(function($method) {
                if(collect([
                    '__call',
                    '__construct',
                    'canHandle',
                    'getHandlerMethod',
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

    public function ast()
    {
        return $this->file->ast();
    }
}