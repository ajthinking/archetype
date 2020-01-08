<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use BadMethodCallException;

use Ajthinking\PHPFileManipulator\Resolvers\ResourceResolver;
use Ajthinking\PHPFileManipulator\Resolvers\TemplateResolver;
use Ajthinking\PHPFileManipulator\Resolvers\QueryBuilderResolver;

trait CanUseSnippets
{
    public function snippet($name, $replacementPairs = []) {

        // $file = $this->in(
        //     base_path('packages/Ajthinking/PHPFileManipulator/src/Snippets')
        // )->get('classMethod', '___HAS_MANY_METHOD___');

        // This is how we want it to work
        $instance = $this->in(
            'packages/Ajthinking/PHPFileManipulator/src/Snippets'
        )->getNode('___HAS_MANY_METHOD___');
    } 
}