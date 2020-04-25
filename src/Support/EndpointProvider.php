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
        '__call',
        '__construct',
        'aliases',
        'canHandle',
        'getEndpoints',
        'getHandlerMethod',
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

    protected function canUseReflection()
    {
        return $this->file->getReflection() && !$this->file->hasModifications();
    }
}