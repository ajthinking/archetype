<?php

use Archetype\Facades\LaravelFile;
use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

use Archetype\Support\AST\ASTQueryBuilder;

it('can be instanciated using an ast object', function() {
	$ast = LaravelFile::load('app/Models/User.php')->ast();
	
	$ASTQB = new ASTQueryBuilder($ast);

	$this->assertInstanceOf(
		ASTQueryBuilder::class,
		$ASTQB
	);
});

it('will return instance of itself on chain', function() {
	$ast = LaravelFile::load('app/Models/User.php')->ast();

	$result = (new ASTQueryBuilder($ast))
		->class();

	$this->assertInstanceOf(
		ASTQueryBuilder::class,
		$result
	);
});

it('can query deep', function() {
	$result = LaravelFile::load(
		'database/migrations/2014_10_12_000000_create_users_table.php'
	)
		->astQuery() // get a ASTQueryBuilder
		->classMethod()
			->where('name->name', 'up')
		->staticCall()
			->where('class', 'Schema')
			->where('name->name', 'create')
		->args
		->value
		->value
		->get() // exit ASTQueryBuilder, get a Collection
		->first();
		
	$this->assertEquals($result, 'users');
});

it('can query with explicit class names', function() {
	// Short node names may be amgigious. If needed, refer to explicit class names
	$names = PHPFile::fromString('CONST CHANNEL = "tv4", TIME = "20:00"')
		->astQuery()
		->traverseIntoClass(\PhpParser\Node\Stmt\Const_::class) // const declaration outside of class
		->traverseIntoClass(\PhpParser\Node\Const_::class) // one of potentially many assignments
		->name->name
		->get();

	$this->assertEquals($names->all(), ['CHANNEL', 'TIME']);
});

it('can query beyond an array', function() {
	$matches = PHPFile::fromString('hey("0h")')
		->astQuery()
		->funcCall()
		->args
		->string()
		->get();
	
	$this->assertCount(1, $matches);
});

it('can query beyond an array using in a where closure', function() {
	$matches = PHPFile::fromString('hey("0h")')
		->astQuery()
		->funcCall()
		->args
		->where(function($query) {
			return $query->string()
				->get()->isNotEmpty();
		})
		->get();
	
	$this->assertCount(1, $matches);
});

// it('can filter on arguments', function() {
// 	$matches = PHPFile::fromString('work("0h")')
// 		->astQuery()
// 		->funcCall()
// 		->where(function($query) {
// 			return $query
// 				->args // should work!
// 				->where('value', '1h')
// 				->get()->isNotEmpty();
// 		})
// 		->get();
	
// 	$this->assertCount(1, $matches);
// });

context('when searching method chains', function() {
	$code = "<?php start()->work('1h')->work('2h')->work('3h')";

	it('will match all methodCalls by default', function() use($code) {
		$matches = PHPFile::fromString($code)
			->astQuery()
			->methodCall()
			->get();
		
		$this->assertCount(3, $matches);
	});

	it('will match only the outer methodCall when doing a shallow query', function() use($code) {
		$matches = PHPFile::fromString($code)
			->astQuery()
			->expression()
			->shallow()
			->methodCall()
			->get();
		
		$this->assertCount(1, $matches);
		$this->assertEquals('3h', $matches->first()->args[0]->value->value);
	});

	// it('can filter on multiple arguments', function() use($code) {
	// 	$matches = PHPFile::fromString($code)
	// 		->astQuery()
	// 		->methodCall()
	// 		->where(function($query) {
	// 			return $query->shallow()
	// 				->arg()
	// 				->tap(function($node, $tree) {
	// 					dump($tree->first()->result[0]->value->value);
	// 				})
					
	// 				->where('value->value', '2h')
	// 				->get()->isNotEmpty();
	// 		})
	// 		->get();
		
	// 	$this->assertCount(1, $matches);
	// });	
});

