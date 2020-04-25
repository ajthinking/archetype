<?php

namespace PHPFileManipulator\Traits;

use BadMethodCallException;
use ReflectionClass;
use Illuminate\Support\Str;

trait ExposesResourceMethodsAsEndpoints
{
    public function getEndpoints()
    {
        $methods = $this->ownNonReservedPublicMethods();

        return collect($methods)->map(function($verb) {
            $resourceSignature = collect($this->aliases())->first();
            $verbMap = [
                'get' => $resourceSignature,
                'set' => $resourceSignature,
                'add' => 'add' . Str::studly($resourceSignature),
                'remove' => 'remove' . Str::studly($resourceSignature),
            ];
            
            return $verbMap[$verb];
        })->unique()->toArray();
    }  
}