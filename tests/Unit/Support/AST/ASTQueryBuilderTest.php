<?php

use Archetype\Facades\LaravelFile;
use Archetype\Facades\PHPFile;
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
	$names = PHPFile::fromString('<?php CONST CHANNEL = "tv4", TIME = "20:00";')
		->astQuery()
		->traverseIntoClass(\PhpParser\Node\Stmt\Const_::class) // const declaration outside of class
		->traverseIntoClass(\PhpParser\Node\Const_::class) // one of potentially many assignments
		->name->name
		->get();

	$this->assertEquals($names->all(), ['CHANNEL', 'TIME']);
});
