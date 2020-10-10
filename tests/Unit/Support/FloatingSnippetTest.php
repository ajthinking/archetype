<?php

use PhpParser\Node\Stmt\ClassMethod;

use Archetype\Support\Snippet;
use Archetype\Endpoints\EndpointProvider;
use Archetype\Support\AST\Visitors\FormattingRemover;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use PhpParser\BuilderFactory;

class FloatingSnippetTest extends Archetype\Tests\FileTestCase
{
    /** @test
     * @group only
    */
    public function it_can_create_a_snippet_without_position_attributes()
    {
        $manual = $this->belongsToMethod('Demo');
        $fromSnippet = Snippet::___HAS_MANY_METHOD___();
        
        $fromSnippet = FormattingRemover::on($fromSnippet);
        
        $disabled = [
            'startLine',
            'startTokenPos',
            'endLine',
            'endTokenPos',
        ];

        foreach($disabled as $key) {
            $this->assertEquals(
                -1,
                $fromSnippet->getAttribute($key)
            );
        }
        

        // dd(
        //     $manual->getAttributes(),
        //     $fromSnippet->getAttributes()
        // );
    }
    
    // Manually creating a Laravel belongsTo method
    // We will not see any row numbers or token indexes here
    protected function belongsToMethod($target)
    {
        $factory = new BuilderFactory;
        $name = Str::belongsToMethodName($target);
        $targetClass = class_basename($target);
        $targetInDocBlock = Str::hasManyDocBlockName($target);

        return $factory->method($name)
            ->makePublic()
            ->setDocComment("/**
                            * Get the associated $targetInDocBlock
                            */")
            ->addStmt(
                new \PhpParser\Node\Stmt\Return_(
                    new \PhpParser\Node\Expr\MethodCall(
                        new \PhpParser\Node\Expr\Variable('this'),
                        'belongsTo',
                        [
                            new \PhpParser\Node\Arg(
                                new \PhpParser\Node\Expr\ClassConstFetch(
                                    new \PhpParser\Node\Name($targetClass),
                                    'class'
                                )
                            )
                        ]
                    )
                )
            )    
                
            ->getNode();
    }
    

    // protected function belongsTo($targets)
    // {
    //     return $this->file->astQuery()
    //         ->class()
    //         ->insertStmts(
    //             collect(Arr::wrap($targets))->map(function($target) {
    //                 return $this->belongsToMethod($target);
    //             })->toArray()
    //         )->commit()
    //         ->end()
    //         ->continue();
    // }    
}
