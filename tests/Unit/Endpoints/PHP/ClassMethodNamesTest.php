<?php

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\Variable;

class ClassMethodNamesTest extends Archetype\Tests\TestCase
{
    /** @test */
    public function it_can_retrieve_class_method_names()
    {
        $file = PHPFile::load('app/Console/Kernel.php');

        $this->assertTrue(
            $file->methodNames() === ['schedule', 'commands']
        );
    }   
}