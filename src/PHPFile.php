<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\Traits\DelegatesAPICalls;
use Ajthinking\PHPFileManipulator\Traits\HasIO;
use Ajthinking\PHPFileManipulator\Traits\HasTemplates;

class PHPFile
{
    use DelegatesAPICalls;
    use HasIO;
    use HasTemplates;

    public function resources() {
        return collect([
            'namespace',
            'uses',
            'className',
            'classExtends',
            'classImplements',
            'classMethods',
            'classMethodNames',            
        ]);
    }

    public function templates() {
        return collect(
            //
        );
    }    
}