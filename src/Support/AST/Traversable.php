<?php

namespace PHPFileManipulator\Support\AST;

class Traversable
{
    const UNTIL = [
        'class' => \PhpParser\Node\Stmt\Class_::class,
        'method' => \PhpParser\Node\Stmt\ClassMethod::class,
    ];
}