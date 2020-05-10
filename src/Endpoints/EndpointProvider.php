<?php

namespace PHPFileManipulator\Endpoints;

use PHPFileManipulator\PHPFile;
use Illuminate\Support\Str;
use PHPFileManipulator\Traits\ExposesPublicMethodsAsEndpoints;
use PHPFileManipulator\Traits\HasDirectiveHandlers;
use ReflectionClass;
use ReflectionMethod;
use PHPFileManipulator\Support\Types;

abstract class EndpointProvider
{
    use ExposesPublicMethodsAsEndpoints;
    use HasDirectiveHandlers;

    protected $reservedMethods = [
        '__call',
        '__construct',
        'canHandle',
        'getEndpoints',
    ];

    protected $directives;
    
    public function __construct(PHPFile $file = null)
    {
        $this->file = $file;

        // proxy directives
        $this->directives = $this->file ? $this->file->directives() : [];
    }

    public function canHandle($signature, $args)
    {
        return (boolean) $this->getHandlerMethod($signature, $args);
    }

    protected function getHandlerMethod($signature, $args)
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