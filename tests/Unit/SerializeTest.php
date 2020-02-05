<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;
use PhpParser\ParserFactory;
use PhpParser\JsonDecoder;

use PHPFile;
use LaravelFile;

class SerializeTest extends FileTestCase
{
    /** @test */
    public function it_can_serialize_simple_files()
    {
        $code = '
            <?php
            
            /** @param string $msg */
            function printLine($msg) {
                echo $msg, "\n";
            }
        ';
        
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        
        $ast = $parser->parse($code);
        $text = json_encode($ast);
        $stmts = (new JsonDecoder())->decode($text);

        $this->assertTrue(
            is_array($stmts)
        );
    }

    /** @test */
    public function it_can_serialize_complex_files()
    {
        $code = file_get_contents(
            base_path('app/User.php')
        );

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        
        $ast = $parser->parse($code);
        $text = json_encode($ast);
        $stmts = (new JsonDecoder())->decode($text);

        $this->assertTrue(
            is_array($stmts)
        );
    }    
}
