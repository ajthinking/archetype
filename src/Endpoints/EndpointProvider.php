<?php

namespace PHPFileManipulator\Endpoints;

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
        return defined('static::aliases') ? static::aliases : [
            Str::camel(class_basename(static::class))
        ];
    }
    
    protected function primaryName()
    {
        return collect($this->aliases())->first();
    }    

    public function canHandle($signature, $args)
    {
        return (boolean) $this->getHandlerMethod($signature, $args);
    }

    public function getHandlerMethod($signature, $args)
    {
        return $this->ownNonReservedPublicMethods()->contains($signature) ? $signature : false;
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