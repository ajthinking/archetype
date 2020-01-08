<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\Traits\DelegatesAPICalls;
use Ajthinking\PHPFileManipulator\Traits\HasIO;
use Ajthinking\PHPFileManipulator\Traits\HasTemplates;
use Ajthinking\PHPFileManipulator\Traits\CanUseSnippets;

class PHPFile
{
    use DelegatesAPICalls;
    use HasIO;
    use HasTemplates;
    use CanUseSnippets;

    protected $resources = [
        Resources\PHP\NamespaceResource::class,
        Resources\PHP\Uses::class,
        Resources\PHP\ClassName::class,
        Resources\PHP\ClassExtends::class,
        Resources\PHP\ClassImplements::class,
        Resources\PHP\ClassMethods::class,
        Resources\PHP\ClassMethodNames::class,            
    ];

    public function resources() {
        return collect((new self)->resources);
    }

    public function templates() {
        return collect(
            //
        );
    }    
}