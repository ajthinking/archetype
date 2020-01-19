<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;
use PHPFile;
use LaravelFile;
use PHPFileManipulator\Support\AST\ASTQueryBuilder;
use PHPFileManipulator\Support\QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ASTQueryBuilderTest extends TestCase
{
    /** @test
    */
    public function it_can_be_instanciated_using_an_ast_object()
    {
        $ast = LaravelFile::load('app/User.php')->ast;
        
        $ASTQB = new ASTQueryBuilder($ast);

        $this->assertInstanceOf(
            ASTQueryBuilder::class,
            $ASTQB
        );
    }

    /** @test
     * @group only
    */
    public function it_will_return_instance_of_itself_on_chain()
    {
        $ast = LaravelFile::load('app/User.php')->ast;

        $result = (new ASTQueryBuilder($ast))
            ->class();

        $this->assertInstanceOf(
            ASTQueryBuilder::class,
            $result
        );
    }    
    
    /** @wiptest
    */
    public function it_can_be_query_deep()
    {
        $ast = LaravelFile::load('app/User.php')->ast;
        
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