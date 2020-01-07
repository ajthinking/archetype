<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit;

use Ajthinking\PHPFileManipulator\Tests\TestCase;
use PHPFile;
use LaravelFile;
use Ajthinking\PHPFileManipulator\Support\QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
    
    /** @test */
    public function it_can_filter_with_in_method()
    {
        $this->assertCount(
            1, LaravelFile::in('public')->get()
        );

        $this->assertCount(
            5, LaravelFile::in('app/Http/Controllers/Auth')->get()
        );        
    }
    
    /** @test */
    public function it_can_filter_with_where_method()
    {
        $this->assertCount(
            0, LaravelFile::in('public')->where('className', 'User')->get()
        );
        
        $this->assertCount(
            1, LaravelFile::in('app')->where('className', '=', 'User')->get()
        );        
    }
    
    /** @test */
    public function it_can_filter_with_closure()
    {
        $this->assertCount(
            2, LaravelFile::in('database/migrations')->where(function($file) {
                return preg_match('/^Create.*Table$/', $file->className()); 
            })->get()
        );
    }
    
    /** @test */
    public function it_can_query_non_class_files_and_files_missing_extend()
    {
        $files = LaravelFile::where('classExtends', 'Controller')->get();
        $this->assertTrue(
            $files->count() > 0
        );
    }
    
    /** @test */
    public function it_can_chain()
    {
        $files = LaravelFile::where('classExtends', 'Controller')
            ->where('namespace', 'App\Http\Controllers\Auth')
            ->where(function($file) {
                return Str::length($file->contents) > 1000; 
            })->get();

        $this->assertCount(
            2, $files
        );
    }     
}
