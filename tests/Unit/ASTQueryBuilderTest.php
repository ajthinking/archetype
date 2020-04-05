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
    public function it_can_query_deep()
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
    
    /** @test */
    public function it_can_flatten_method_call_chains()
    {
        $result = LaravelFile::load(__DIR__ . '/../samples/chained_migration_table_statement.php')
            ->astQuery()
            ->expression()
            ->shallow()
            ->methodCall()
            ->whereChainingOn('table')
            ->flatten() // this will later return a formatted response and we must exit the query
            ->get();


        //dd($result);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
}