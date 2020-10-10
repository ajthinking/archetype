<?php

namespace Archetype\Endpoints\Laravel;

use Archetype\Endpoints\EndpointProvider;
use Archetype\Support\Snippet;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use PhpParser\BuilderFactory;

class BelongsTo extends EndpointProvider
{
    /**
     * @example Add a belongsTo relationship method
     * @source $file->belongsTo('Company')
     */
    public function belongsTo($targets)
    {
        return $this->add($targets);
    }

    protected function addOld($targets)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmts(
                collect(Arr::wrap($targets))->map(function($target) {
                    return Snippet::___HAS_MANY_METHOD___([
                        '___HAS_MANY_METHOD___' => Str::hasManyMethodName($target),
                        '___TARGET_CLASS___' => class_basename($target),
                        '___TARGET_IN_DOC_BLOCK___' => Str::hasManyDocBlockName($target)
                    ]);
                })->toArray()
            )->commit()
            ->end()
            ->continue();
    }

    protected function add($targets)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmts(
                collect(Arr::wrap($targets))->map(function($target) {
                    return $this->belongsToMethod($target);
                })->toArray()
            )->commit()
            ->end()
            ->continue();
    }

    // A VEEEERY EXPLICIT APPROACH
    // ANOTHER IDEA: STRIP THE LINES/ATTRIBUTES FROM THE SNIPPET CLASS
    // THAT WILL LEAVE US TO RELY ON PRETTY PRINTING WHICH IS NOT THAT BAD??
    protected function belongsToMethod($target)
    {
        $factory = new BuilderFactory;
        $name = Str::belongsToMethodName($target);
        $targetClass = class_basename($target);
        $targetInDocBlock = Str::hasManyDocBlockName($target);

        return $factory->method($name)
            ->makePublic()
            ->setDocComment("/**
                            * Get the associated $targetInDocBlock
                            */")
            ->addStmt(
                new \PhpParser\Node\Stmt\Return_(
                    new \PhpParser\Node\Expr\MethodCall(
                        new \PhpParser\Node\Expr\Variable('this'),
                        'belongsTo',
                        [
                            new \PhpParser\Node\Arg(
                                new \PhpParser\Node\Expr\ClassConstFetch(
                                    new \PhpParser\Node\Name($targetClass),
                                    'class'
                                )
                            )
                        ]
                    )
                )
            )    
                
            ->getNode();
    }

   
}