<?php

namespace PHPFileManipulator;

use PHPFileManipulator\Traits\DelegatesAPICalls;
use PHPFileManipulator\Traits\HasIO;
use PHPFileManipulator\Traits\HasTemplates;

class PHPFile
{
    use DelegatesAPICalls;
    use HasIO;
    use HasTemplates;

    protected $endpoints = [
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