<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;
use PHPFile;
use LaravelFile;
use PHPFileManipulator\Support\ASTQueryBuilder;
use PHPFileManipulator\Support\QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ASTQueryBuilderTest extends TestCase
{
    /** @test
    */
    public function it_can_be_instanciated_using_an_ast_object()
    {
        $ast = $this->laravelUserFile()->ast;
        
        $ASTQB = new ASTQueryBuilder($ast);

        $this->assertInstanceOf(
            ASTQueryBuilder::class,
            $ASTQB
        );
    }
    
    /** @wiptest
    */
    public function it_can_be_query_deep()
    {
        $ast = $this->laravelUserFile()->ast;
        
        $ASTQB = new ASTQueryBuilder($ast);

        $ASTQB              // [Namespace_]
            ->class()       // Class_
            ->property()    // [Property,                             Property,                 Property]
            ->array()       // [Array_,                               Array_,                   BRANCH_TERMINATED]
            ->items()       // [[ArrayItem, ArrayItem, ArrayItem],    [ArrayItem, ArrayItem]]
            ->get();        // return AST

            
        $this->assertInstanceOf(
            ASTQueryBuilder::class,
            $ASTQB
        );
    }    
}