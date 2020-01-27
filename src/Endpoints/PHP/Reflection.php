<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Support\EndpointProvider;
use ReflectionClass;
use Exception;

class Reflection extends EndpointProvider
{
    public function __call($method, $args)
    {
        $reflection = $this->getReflection();
        return $reflection->$method(...$args);
    }

    public function getReflection()
    {
        $class = "\\" . $this->file->namespace() ."\\" . $this->file->className();

        try {
            return new ReflectionClass($class);
        } catch(Exception $e) {
            dd("Could not reflect class $class");
        }
    }

    public function getHandlerMethod($signature, $args)
    {
        return $this->getReflectionMethods()->first(function($name) use($signature) {
            return $name == $signature;
        });
    }

    private function getReflectionMethods()
    {
        return collect('getMethods');
        return collect(
            (new ReflectionClass(
                ReflectionClass::class
            ))->getMethods()
        )->map(function($method) {
            return $method->name;
        })->filter(function($name) {
            return !collect([
                "__clone",
                "__construct",
                "__toString",
            ])->contains($name);
        });        
    }
}