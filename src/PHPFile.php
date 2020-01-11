<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Traits\DelegatesAPICalls;

class PHPFile
{
    use DelegatesAPICalls;

    protected $endpoints = [

        // Utillities
        Endpoints\PHP\IO::class,
        Endpoints\PHP\Template::class,

        // Resources
        Endpoints\PHP\NamespaceResource::class,
        Endpoints\PHP\Uses::class,
        Endpoints\PHP\ClassName::class,
        Endpoints\PHP\ClassExtends::class,
        Endpoints\PHP\ClassImplements::class,
        Endpoints\PHP\ClassMethods::class,
        Endpoints\PHP\ClassMethodNames::class,            
    ];

    public function endpoints() {
        return collect((new self)->endpoints);
    }

    public function templates() {
        return collect(
            //
        );
    }    
}