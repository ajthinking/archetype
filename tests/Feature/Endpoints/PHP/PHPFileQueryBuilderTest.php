<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;
use Archetype\Tests\Support\TestablePHPFileQueryBuilder;
use Illuminate\Support\Collection;

it('can instanciate via php or laravel file with in method', function() {
	PHPFile::in('app')
		->assertInstanceOf(TestablePHPFileQueryBuilder::class);
});

it('will return a collection on get', function() {
	$this->assertInstanceOf(
		Collection::class,
		PHPFile::in('app')->get()
	);
});
    
it('can filter with in method', function() {
	PHPFile::in('app/Http/Middleware')->assertMatchCount(8);
});

it('can filter with where equals className', function() {
	PHPFile::in('app/Models')->where('className', 'User')
		->assertMatchCount(1);
});

it('can filter with where equals className explicitly', function() {
	PHPFile::in('app/Models')->where('className', '=', 'User')
		->assertMatchCount(1);
});

it('can filter with where not equals className', function() {
	PHPFile::in('app/Models')->where('className', '!=', 'User')
		->assertMatchCount(0);
});

it('can filter with where use contains class', function() {
	PHPFile::in('app')
		->where('use', 'contains', 'Illuminate\Contracts\Auth\MustVerifyEmail')
		->assertMatchCount(1);
});

it('can filter with like', function() {
	PHPFile::in('app')->where('className', 'like', 'Controller')
		->assertMatchCount(1);
});

it('can filter with regex', function() {
	PHPFile::in('app')->where('className', 'matches', '/^Controller/')
		->assertMatchCount(1);
});

it('can filter with in', function() {
	PHPFile::in('app')->where('className', 'in', ['Dog', 'User', 'Cat'])
		->assertMatchCount(1);
});

it('can filter with count', function() {
	PHPFile::in('app')->where('use', 'count', 4)
		->assertMatchCount(3);
});

it('can filter with where method using an array', function() {
	PHPFile::in('app')
		->where([
			['className', 'like', 'provider'],
			['methodNames', 'contains', 'configureRateLimiting']
		])->assertMatchCount(1);
});

it('can add filters with andWhere', function() {
	PHPFile::in('app')
		->where('className', 'like', 'provider')
		->andWhere('methodNames', 'contains', 'configureRateLimiting')
		->assertMatchCount(1);
});

it('can filter with closure', function() {
	PHPFile::in('app')->where(function ($file) {
		return preg_match('/^.*Kernel$/', $file->extends());
	})->assertMatchCount(2);
});
    
it('can query all files in application root including non classes without extend', function() {
	PHPFile::where('extends', 'Authenticatable')
		->assertMatchCount(1);
});
    
it('can chain multiple where clauses', function() {
	PHPFile::where('extends', 'ServiceProvider')
		->where('methodNames', 'contains', 'boot')
		->where(function ($file) {
			return $file->className() == 'AuthServiceProvider';
		})->assertMatchCount(1);
});
    
it('can get first match', function() {
	PHPFile::in('public')
		->first()
		->assertInstanceOf(\Archetype\Tests\Support\TestablePHPFile::class);
});

it('will accept forbidden directories when explicitly passed', function() {
	PHPFile::in('vendor/ajthinking/archetype/src/snippets')
		->first()
		->assertInstanceOf(\Archetype\Tests\Support\TestablePHPFile::class);
});