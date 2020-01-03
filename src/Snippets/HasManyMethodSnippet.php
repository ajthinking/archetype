<?php

namespace Ajthinking\PHPFileManipulator\Snippets;

use Ajthinking\PHPFileManipulator\BaseSnippet;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Node;

class HasManyMethodSnippet extends BaseSnippet
{
    public function renderAST($targetClass)
    {
        $factory = new BuilderFactory;

        $method = $factory->method('someMethod')
            ->makePublic()
            ->makeAbstract() // ->makeFinal()
            ->setReturnType('bool') // ->makeReturnByRef()
            ->addParam($factory->param('someParam')->setType('SomeClass'))
            ->setDocComment('/**
                              * This method does something.
                              *
                              * @param SomeClass And takes a parameter
                              */')
            ->getNode();    

        return;
    }
}