<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\TestCase;
use PHPFile;
use LaravelFile;
use PHPFileManipulator\Endpoints\PHP\FileQueryBuilder;
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
            FileQueryBuilder::class,
            PHPFile::in('app')
        );

        $this->assertInstanceOf(
            FileQueryBuilder::class,
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
        
        $this->assertInstanceOf(
            Collection::class,
            LaravelFile::get()
        ); 
    }
    
    /** @test */
    public function it_can_filter_with_in_method()
    {
        $this->assertCount(
            1, LaravelFile::in('public')->get()
        );

        $this->assertCount(
            6, LaravelFile::in('app/Http/Controllers/Auth')->get()
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

        $this->assertCount(
            2, LaravelFile::in('database/migrations')->where('className', '!=', 'CreateUsersTable')->get()
        );        

        $this->assertCount(
            1, LaravelFile::in('app')->where('uses', 'contains', 'Illuminate\Contracts\Auth\MustVerifyEmail')->get()
        );

        $this->assertCount(
            7, LaravelFile::in('app')->where('className', 'like', 'Controller')->get()
        );

        $this->assertCount(
            7, LaravelFile::in('app')->where('className', 'like', 'controller')->get()
        );        
        
        $this->assertCount(
            1, LaravelFile::in('app')->where('className', 'matches', '/^Controller/')->get()
        );
        
        $this->assertCount(
            1, LaravelFile::in('app')->where('className', 'in', ['Dog', 'User', 'Cat'])->get()
        );
        
        $this->assertCount(
            2, LaravelFile::in('app')->where('uses', 'count', 4)->get()
        );        
    }

    /** @test */
    public function it_can_filter_with_where_method_using_an_array()
    {
        $this->assertCount(
            1, LaravelFile::in('app')->where([
                ['className', 'like', 'controller'],
                ['classMethodNames', 'contains', 'create']
            ])->get()
        );        
    }

    /** @test */
    public function it_can_add_filters_with_andWhere()
    {
        $this->assertCount(
            1, LaravelFile::in('app')
                ->where('className', 'like', 'controller')
                ->andWhere('classMethodNames', 'contains', 'create')
                ->get()
        );        
    }    

    /** @test */
    public function it_can_filter_with_closure()
    {
        $this->assertCount(
            3, LaravelFile::in('database/migrations')->where(function($file) {
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
                return $file->className() == 'LoginController'; 
            })->get();

        $this->assertCount(
            1, $files
        );
    }     
}