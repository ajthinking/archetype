<?php

namespace Ajthinking\PHPFileManipulator\Tests\Unit\Resources;

use Ajthinking\PHPFileManipulator\Tests\TestCase;
use Ajthinking\PHPFileManipulator\LaravelFile;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\Variable;

class ClassMethodsTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_class_method_names()
    {
        $file = $this->userFile();

        $this->assertTrue(
            $file->classMethodNames() === ['cars']
        );
    }

    /** @test */
    public function it_can_retrieve_class_method_asts()
    {
        $methods = $this->userFile()->classMethods();

            collect($methods)->each(function($method) {
                $this->assertInstanceOf(ClassMethod::class, $method);
            });
    }
    
    /** @test */
    public function it_can_add_class_methods()
    {
        $factory = new BuilderFactory;
        $file = $this->laravelUserFile();

        $file = $file->addClassMethods([
            $factory->method('insertedMethod')
                ->makeProtected() // ->makePublic() [default], ->makePrivate()
                ->addParam($factory->param('someParam')->setDefault('test'))
                // it is possible to add manually created nodes
                ->addStmt(new Print_(new Variable('someParam')))
                ->getNode()            
        ]);

        $this->assertContains(
            'insertedMethod', $file->classMethodNames()
        );
    }    
}