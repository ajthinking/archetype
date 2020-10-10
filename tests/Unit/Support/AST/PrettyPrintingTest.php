<?php

use PhpParser\{Lexer, NodeTraverser, NodeVisitor, Parser, PrettyPrinter};
use PhpParser\Error;
use PhpParser\ParserFactory;

use Archetype\Support\PSR2PrettyPrinter;
use PhpParser\BuilderFactory;
use PhpParser\Node;

class PrettyPrintingTest extends Archetype\Tests\FileTestCase
{
    public function setup() :void
    {
        parent::setUp();

        $this->code = <<< 'CODE'
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
    }

    /** @test */    
    public function two_line_breaks_separate_methods()
    { 
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $prettyPrinter = new PSR2PrettyPrinter;

        $stmts = $parser->parse($this->code);
        
        $code = $prettyPrinter->prettyPrint($stmts);
        
        $this->assertStringContainsString(
            ';' . PHP_EOL . PHP_EOL . '    public function fly()',
            LaravelFile::fromString($this->code)->table('users_table')->render()
        );
        
        $this->assertStringContainsString(
            '}' . PHP_EOL . PHP_EOL . '    public function sleeping()',
            LaravelFile::fromString($this->code)->table('users_table')->render()
        );        
    }

    /** @test */       
    public function there_is_not_a_missing_space_between_methods_when_format_preserving_pretty_printing()
    {   
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
        
        $oldStmts = $parser->parse($this->code);
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

        $this->markTestIncomplete();
    }

    protected function makeMethod($name)
    {
        return (new BuilderFactory)->method($name)->getNode();        
    }
}