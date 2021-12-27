<?php

use Archetype\Endpoints\PHP\PHPFileQueryBuilder;
use Archetype\Endpoints\Laravel\LaravelFileQueryBuilder;
use Archetype\Facades\LaravelFile;
use Archetype\Facades\PHPFile;

it('can_instanciate_via_php_or_laravel_file_with_in_method', function() {
	$this->assertInstanceOf(
		PHPFileQueryBuilder::class,
		PHPFile::in('app')
	);

	$this->assertInstanceOf(
		LaravelFileQueryBuilder::class,
		LaravelFile::in('app')
	);
});

it('will_return_a_collection_on_get', function() {
	$this->assertInstanceOf(
		\Illuminate\Support\Collection::class,
		LaravelFile::in('app')->get()
	);
	
	$this->assertInstanceOf(
		\Illuminate\Support\Collection::class,
		LaravelFile::get()
	);
});
    
it('can_filter_with_in_method', function() {
	$this->assertCount(
		1,
		LaravelFile::in('public')->get()
	);

	$this->assertCount(
		8,
		LaravelFile::in('app/Http/Middleware')->get()
	);
});

it('can_filter_with_where_method', function() {
	$this->assertCount(
		0,
		LaravelFile::in('public')->where('className', 'User')->get()
	);
	
	$this->assertCount(
		1,
		LaravelFile::in('app')->where('className', '=', 'User')->get()
	);

	$this->assertCount(
		4,
		LaravelFile::in('app/Providers')->where('className', '!=', 'AppServiceProvider')->get()
	);

	$this->assertCount(
		1,
		LaravelFile::in('app')->where('use', 'contains', 'Illuminate\Contracts\Auth\MustVerifyEmail')->get()
	);

	$this->assertCount(
		1,
		LaravelFile::in('app')->where('className', 'like', 'Controller')->get()
	);

	$this->assertCount(
		1,
		LaravelFile::in('app')->where('className', 'like', 'controller')->get()
	);
	
	$this->assertCount(
		1,
		LaravelFile::in('app')->where('className', 'matches', '/^Controller/')->get()
	);
	
	$this->assertCount(
		1,
		LaravelFile::in('app')->where('className', 'in', ['Dog', 'User', 'Cat'])->get()
	);

	$this->assertCount(
		3,
		LaravelFile::in('app')->where('use', 'count', 4)->get()
	);
});

it('can_filter_with_where_method_using_an_array', function() {
	$this->assertCount(
		1,
		LaravelFile::in('app')->where([
			['className', 'like', 'provider'],
			['methodNames', 'contains', 'configureRateLimiting']
		])->get()
	);
});

it('can_add_filters_with_andWhere', function() {
	$this->assertCount(
		1,
		LaravelFile::in('app')
			->where('className', 'like', 'provider')
			->andWhere('methodNames', 'contains', 'configureRateLimiting')
			->get()
	);
});

it('can_filter_with_closure', function() {
	$this->assertCount(
		2,
		LaravelFile::in('app')->where(function ($file) {
			return preg_match('/^.*Kernel$/', $file->extends());
		})->get()
	);
});
    
it('can_query_non_class_files_and_files_missing_extend', function() {
	$files = LaravelFile::where('extends', 'Authenticatable')->get();
	$this->assertTrue(
		$files->count() > 0
	);
});
    
it('can_chain', function() {
	$files = LaravelFile::where('extends', 'ServiceProvider')
		->where('methodNames', 'contains', 'boot')
		->where(function ($file) {
			return $file->className() == 'AuthServiceProvider';
		})->get();

	$this->assertCount(
		1,
		$files
	);
});
    
it('has_a_first_method', function() {
	$this->assertInstanceOf(
		\Archetype\LaravelFile::class,
		LaravelFile::in('public')->first()
	);
});
