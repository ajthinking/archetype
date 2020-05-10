<?php

namespace PHPFileManipulator\Endpoints;

use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;
use PHPFileManipulator\Traits\ExposesPublicMethodsAsEndpoints;
use ReflectionClass;
use ReflectionMethod;
use PHPFileManipulator\Support\Types;

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

        // proxy directives
        $this->intermediateDirectives = $this->file ? $this->file->directives() : [];
    }

    public function directives($directives = null)
    {
        if(!$directives) return $this->intermediateDirectives;
        
        $this->intermediateDirectives = $directives;
        
        return $this;
    }

    public function directive($key, $value = Types::NO_VALUE)
    {
        if($value === Types::NO_VALUE) return $this->intermediateDirectives[$key] ?? null;
        
        $this->intermediateDirectives[$key] = $value;
        
        return $this;
    }    

    public static function aliases()
    {
        return defined('static::aliases') ? static::aliases : [
            Str::camel(class_basename(static::class))
        ];
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