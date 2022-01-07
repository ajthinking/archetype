<?php

use Archetype\Endpoints\PHP\PHPFileQueryBuilder;
use Archetype\Endpoints\Laravel\LaravelFileQueryBuilder;
use Archetype\Facades\LaravelFile;
use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can instanciate via php or laravel file with in method', function() {
	$this->assertInstanceOf(
		PHPFileQueryBuilder::class,
		PHPFile::in('app')
	);

	$this->assertInstanceOf(
		LaravelFileQueryBuilder::class,
		LaravelFile::in('app')
	);
});

it('will return a collection on get', function() {
	$this->assertInstanceOf(
		\Illuminate\Support\Collection::class,
		LaravelFile::in('app')->get()
	);
	
	$this->assertInstanceOf(
		\Illuminate\Support\Collection::class,
		LaravelFile::get()
	);
});
    
it('can filter with in method', function() {
	$this->assertCount(
		1,
		LaravelFile::in('public')->get()
	);

	$this->assertCount(
		8,
		LaravelFile::in('app/Http/Middleware')->get()
	);
});

it('can filter with where method', function() {
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

it('can filter with where method using an array', function() {
	$this->assertCount(
		1,
		LaravelFile::in('app')->where([
			['className', 'like', 'provider'],
			['methodNames', 'contains', 'configureRateLimiting']
		])->get()
	);
});

it('can add filters with andWhere', function() {
	$this->assertCount(
		1,
		LaravelFile::in('app')
			->where('className', 'like', 'provider')
			->andWhere('methodNames', 'contains', 'configureRateLimiting')
			->get()
	);
});

it('can filter with closure', function() {
	$this->assertCount(
		2,
		LaravelFile::in('app')->where(function ($file) {
			return preg_match('/^.*Kernel$/', $file->extends());
		})->get()
	);
});
    
it('can query non class files and files missing extend', function() {
	$files = LaravelFile::where('extends', 'Authenticatable')->get();
	$this->assertTrue(
		$files->count() > 0
	);
});
    
it('can chain', function() {
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
    
it('has a first method', function() {
	$this->assertInstanceOf(
		\Archetype\LaravelFile::class,
		LaravelFile::in('public')->first()
	);
});

it('will accept forbidden directories when explicitly passed', function() {
	$file = PHPFile::in(
		'vendor/ajthinking/archetype/src/snippets'
	)->get()->first();

	$this->assertTrue(
		get_class($file) === \Archetype\Tests\Support\TestablePHPFile::class
	);
});