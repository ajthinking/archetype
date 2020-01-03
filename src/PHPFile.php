<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\Traits\DelegatesAPICalls;
use Ajthinking\PHPFileManipulator\Traits\HasIO;
use Ajthinking\PHPFileManipulator\Traits\HasQueryBuilder;
use Ajthinking\PHPFileManipulator\Traits\HasSnippets;

class PHPFile
{
    use DelegatesAPICalls;
    use HasIO;
    use HasQueryBuilder;
    use HasSnippets;

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
}