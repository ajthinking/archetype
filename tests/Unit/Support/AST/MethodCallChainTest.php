<?php

use Archetype\Facades\LaravelFile;
use Archetype\Support\AST\MethodCallChain;

use Archetype\Tests\Support\TestableASTQueryBuilder as ASTQueryBuilder;
use PhpParser\Node\Expr\MethodCall;

use function PHPUnit\Framework\assertInstanceOf;

it('can be instanciated using an ast object', function() {
	$ast = LaravelFile::load('public/index.php')->ast();
	$instance = new MethodCallChain($ast);

	assertInstanceOf(MethodCallChain::class, $instance);
});

it('can flatten chain to an array', function() {
	$code = <<<CODE
	<?php
	initiator()->one()->two()->three();
	CODE;

	$ast = LaravelFile::fromString($code)->ast();
	$rootNode = $ast[0]->expr;
	
	$parts = MethodCallChain::make($rootNode)->flatten();

	$names = collect($parts)->map(function($methodCall) {
		return $methodCall->name->name;
	})->toArray();

	$this->assertEquals(['one', 'two', 'three'], $names);


});

// Str::one()->two()->three()
$file->astQuery()
	->whereCallStackOn(Str::class)
	->get();

// str()->one()->two()->three()	
$file->astQuery()
	->whereCallStackOn(function($query) {
	$query->funcCall()->where('name->name', 'str');
})->get();

// $str->one()->two()->three()	
$file->astQuery()
	->whereCallStackOn(function($query) {
	$query->funcCall()->where('name->name', 'str');
})->get();

