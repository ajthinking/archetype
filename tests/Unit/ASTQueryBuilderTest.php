<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;
use PHPFile;
use LaravelFile;
use PHPFileManipulator\Support\AST\ASTQueryBuilder;
use PHPFileManipulator\Support\QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ASTQueryBuilderTest extends FileTestCase
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

    /** @test */
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
    
    /** @test
    */
    public function it_can_be_query_deep()
    {
        $result = LaravelFile::load(
            'database/migrations/2014_10_12_000000_create_users_table.php'
        )
            ->astQuery() // get a ASTQueryBuilder
            ->method()
                ->named('up')
            ->staticCall()
                ->where('class', 'Schema')
                ->named('create')
            ->args()
            ->value()
            ->value()
            ->get() // exit ASTQueryBuilder, get a Collection   
            ->first();
            
        $this->assertEquals($result, 'users');
    }    
}