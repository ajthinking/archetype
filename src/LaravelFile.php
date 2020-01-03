<?php

namespace Ajthinking\PHPFileManipulator;

use Ajthinking\PHPFileManipulator\PHPFile;

class LaravelFile extends PHPFile 
{
    public function resources() {
        return parent::resources()->concat([
            'casts',
            'fillable',
            'hidden',
            
            'routes',

            'hasOneMethods',
            'hasManyMethods',
            'belongsToMethods',
            'belongsToManyMethods',
        ]);
    }
}