<?php

namespace PHPFileManipulator\Support\AST;

class Traversable
{
    const UNTIL = [
        'class' => \PhpParser\Node\Stmt\Class_::class,
        'closure' => \PhpParser\Node\Expr\Closure::class,
        'const' => \PhpParser\Node\Stmt\Const_::class,
        'method' => \PhpParser\Node\Stmt\ClassMethod::class,
        'methodCall' => \PhpParser\Node\Expr\MethodCall::class,
        'staticCall' => \PhpParser\Node\Expr\StaticCall::class,
        'string' => \PhpParser\Node\Scalar\String_::class,
    ];
}