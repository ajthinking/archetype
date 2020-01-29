<?php

namespace PHPFileManipulator\Support;

use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;
use PHPFileManipulator\Traits\ExposesPublicMethodsAsEndpoints;
use ReflectionClass;
use ReflectionMethod;

abstract class EndpointProvider
{
    use ExposesPublicMethodsAsEndpoints;

    protected $reserved_methods = [
        'aliases'
    ];
    
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

    protected function ast()
    {
        return $this->file->ast();
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