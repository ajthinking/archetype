<?php

namespace Ajthinking\PHPFileManipulator;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Arg;
use PhpParser\BuilderFactory;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use Illuminate\Support\Str;

class LaravelSnippet
{
    public static function hasOneMethod($target, $docComment = false)
    {
        $type = 'hasOne';
        $methodName = Str::camel(
            collect(explode('\\', $target))->last()
        );

        return static::relationshipMethod(
            $methodName,
            $type,
            $target,
            $docComment 
        );
    }

    public static function hasManyMethod($target, $docComment = false)
    {
        $type = 'hasMany';
        $methodName = Str::camel(
            Str::plural(
                collect(explode('\\', $target))->last()
            )
        );

        return static::relationshipMethod(
            $methodName,
            $type,
            $target,
            $docComment 
        );
    }

    public static function belongsToMethod($target, $docComment = false)
    {
        $type = 'belongsTo';
        $methodName = Str::camel(
            collect(explode('\\', $target))->last()
        );

        return static::relationshipMethod(
            $methodName,
            $type,
            $target,
            $docComment 
        );
    }

    public static function belongsToManyMethod($target, $docComment = false)
    {
        $type = 'belongsToMany';
        $methodName = Str::camel(
            Str::plural(
                collect(explode('\\', $target))->last()
            )
        );

        return static::relationshipMethod(
            $methodName,
            $type,
            $target,
            $docComment 
        );
    }    
    
    private static function relationshipMethod($methodName, $type, $target, $docComment)
    {
        $factory = new BuilderFactory;

        return $factory->method($methodName)
            ->addStmt(
                new Return_(
                    new MethodCall(
                        new Variable('this'),
                        $type,
                        [
                            new Arg(
                                new ClassConstFetch(
                                    new Name($target),
                                    'class'
                                )
                            )
                        ]
                    )
                )
            )
            ->setDocComment(
                $docComment ? $docComment : 
                "/**
                * Get the associated $methodName
                */"
            )
            ->getNode();
    }
}