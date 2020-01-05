<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit;

use Ajthinking\PHPFileManipulator\Tests\TestCase;
use PHPFile;
use LaravelFile;
use Ajthinking\PHPFileManipulator\QueryBuilder;
use Illuminate\Support\Collection;


class QueryBuilderTest extends TestCase
{
    /** @test */
    public function pass()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_instanciate_via_php_or_laravel_file_with_in_method()
    {
        $this->assertInstanceOf(
            QueryBuilder::class,
            PHPFile::in('app')
        );

        $this->assertInstanceOf(
            QueryBuilder::class,
            LaravelFile::in('app')
        );        
    }

    /** @test */
    public function it_will_return_a_collection_on_get()
    {
        $this->assertInstanceOf(
            Collection::class,
            LaravelFile::in('app')->get()
        );        
    }    
}
