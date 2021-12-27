<?php

use Archetype\Facades\LaravelFile;
use Archetype\Support\AST\ASTQueryBuilder;

it('can_be_instanciated_using_an_ast_object', function() {
	$ast = LaravelFile::load('app/Models/User.php')->ast();
	
	$ASTQB = new ASTQueryBuilder($ast);

	$this->assertInstanceOf(
		ASTQueryBuilder::class,
		$ASTQB
	);
});

it('will_return_instance_of_itself_on_chain', function() {
	$ast = LaravelFile::load('app/Models/User.php')->ast();

	$result = (new ASTQueryBuilder($ast))
		->class();

	$this->assertInstanceOf(
		ASTQueryBuilder::class,
		$result
	);
});

it('can_query_deep', function() {
	$result = LaravelFile::load(
		'database/migrations/2014_10_12_000000_create_users_table.php'
	)
		->astQuery() // get a ASTQueryBuilder
		->method()
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