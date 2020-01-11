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
        Resources\PHP\NamespaceResource::class,
        Resources\PHP\Uses::class,
        Resources\PHP\ClassName::class,
        Resources\PHP\ClassExtends::class,
        Resources\PHP\ClassImplements::class,
        Resources\PHP\ClassMethods::class,
        Resources\PHP\ClassMethodNames::class,            
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