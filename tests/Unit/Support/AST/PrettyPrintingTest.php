<?php

use Archetype\Facades\LaravelFile;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;

use Archetype\Support\PSR2PrettyPrinter;
use PhpParser\BuilderFactory;

const CODE = <<< 'CODE'
<?php

class Bird
{
	public $name;
	public $gender;

	public function fly()
	{
		return 'flying!';
	}

	public function sleeping()
	{
		return 'zzz...';
	}            
}
CODE;

it('two_line_breaks_separate_methods', function() {
	$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
	$prettyPrinter = new PSR2PrettyPrinter;

	$stmts = $parser->parse(CODE);
	
	$code = $prettyPrinter->prettyPrint($stmts);
	
	$this->assertStringContainsString(
		';' . PHP_EOL . PHP_EOL . '	public function fly()',
		LaravelFile::fromString(CODE)->table('users_table')->render()
	);
	
	$this->assertStringContainsString(
		'}' . PHP_EOL . PHP_EOL . '	public function sleeping()',
		LaravelFile::fromString(CODE)->table('users_table')->render()
	);
});

it('there_is_not_a_missing_space_between_methods_when_format_preserving_pretty_printing', function() {
	$this->markTestIncomplete();
	
	$lexer = new Lexer\Emulative([
		'usedAttributes' => [
			'comments',
			'startLine', 'endLine',
			'startTokenPos', 'endTokenPos',
		],
	]);
	$parser = new Parser\Php7($lexer);
	
	$traverser = new NodeTraverser();
	$traverser->addVisitor(new NodeVisitor\CloningVisitor());
	
	$printer = new PSR2PrettyPrinter();
	
	$oldStmts = $parser->parse(CODE);
	$oldTokens = $lexer->getTokens();
	
	$newStmts = $traverser->traverse($oldStmts);
	
	array_push(
		$newStmts[0]->stmts,
		(new BuilderFactory)->method('eating')->makePublic()->getNode(),
		(new BuilderFactory)->method('drinking')->makePublic()->getNode(),
	);
	
	$newCode = $printer->printFormatPreserving($newStmts, $oldStmts, $oldTokens);

	// THe spaces should be fixed!
	$this->assertMatchesRegularExpression(
		'/\n    \n    public function eating()/',
		$newCode
	);
});
