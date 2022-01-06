<?php

use Archetype\Facades\LaravelFile;
use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

use Archetype\Tests\Support\TestableASTQueryBuilder as ASTQueryBuilder;

it('can be instanciated using an ast object', function() {
	$ast = LaravelFile::load('public/index.php')->ast();
	(new ASTQueryBuilder($ast))->assertInstanceOf(ASTQueryBuilder::class);
});

it('will return instance of itself on chain', function() {
	$ast = PHPFile::load('app/Models/User.php')->ast();

	(new ASTQueryBuilder($ast))
		->class()
		->classMethod()
		->assertInstanceOf(ASTQueryBuilder::class);
});

it('will returns a collection on get', function() {
	(new ASTQueryBuilder([]))
		->assertMatches(collect());
});

it('can queries deep by default which means it will skip intermediate missing layers', function() {
	PHPFile::load('database/migrations/2014_10_12_000000_create_users_table.php')
		->astQuery()
		->classMethod()
		->where('name->name', 'up')
		->staticCall()
		->where('class', 'Schema')
		->where('name->name', 'create')
		->args
		->value
		->value
		->assertMatches(collect(['users']));
});

it('can query with explicit class names', function() {
	// Short node names may be amgigious. If needed, refer to explicit class names
	PHPFile::fromString('CONST CHANNEL = "tv4", TIME = "20:00"')
		->astQuery()
		->traverseIntoClass(\PhpParser\Node\Stmt\Const_::class) // const declaration outside of class
		->traverseIntoClass(\PhpParser\Node\Const_::class) // one of potentially many assignments
		->name->name
		->assertMatches(collect(['CHANNEL', 'TIME']));
});

it('can query beyond an array', function() {
	PHPFile::fromString('hey("0h")')
		->astQuery()
		->funcCall()
		->args
		->string()
		->assertMatchCount(1);
});

it('can traverse into property when result is an array', function() {
	PHPFile::fromString('1;2;')->astQuery() // Two(!) Expression:s
		->expr
		->assertMatchCount(2);
});

it('can query beyond an array using in a where closure', function() {
	PHPFile::fromString('ho("0h")')
		->astQuery()
		->funcCall()
		->where(fn($query) => $query->args->string())
		->assertMatchCount(1);
});

context('when searching method chains', function() {
	it('will match all methodCalls by default', function() {
		PHPFile::fromString('$lets->go()->go()->go()')
			->astQuery()
			->methodCall()
			->assertMatchCount(3);
	});

	it('will match only the outermost methodCall when doing a shallow query', function() {
		PHPFile::fromString("start()->work('1h')->work('2h')->work('3h')")
			->astQuery()
			->expression()
			->shallow()
			->methodCall()
			->arg()
			->string()
			->value
			->assertMatchCount(1)
			->assertMatches(collect('3h'));
	});	
});

test('resolved where closures with matches are considered truthy', function() {
	PHPFile::fromString('class Cool extends Ice {}')
		->astQuery()
		->class()
		->where(fn($query) => $query->where('extends->parts', ['Ice'])->get())
		->assertMatchCount(1);
});

test('unresolved where closures with matches are considered truthy', function() {
	PHPFile::fromString('class Cool extends Ice {}')
		->astQuery()
		->class()
		->where(fn($query) => $query->where('extends->parts', ['Ice']))
		->assertMatchCount(1);
});

test('resolved where closures without matches are considered falsy', function() {
	PHPFile::fromString('class Cool extends Ice {}')
		->astQuery()
		->class()
		->where(fn($query) => $query->where('extends->parts', ['Baby'])->get())
		->assertMatchCount(0);
});

test('unresolved where closures without matches are considered falsy', function() {
	PHPFile::fromString('class Cool extends Ice {}')
		->astQuery()
		->class()
		->where(fn($query) => $query->where('extends->parts', ['Baby']))
		->assertMatchCount(0);
});

test('where closures returning true are considered truthy', function() {
	PHPFile::fromString('class Cool extends Ice {}')
		->astQuery()
		->class()
		->where(fn($_) => true)
		->assertMatchCount(1);
});

test('where closures returning false are considered falsy', function() {
	PHPFile::fromString('class Cool extends Ice {}')
		->astQuery()
		->class()
		->where(fn($_) => false)
		->assertMatchCount(0);
});