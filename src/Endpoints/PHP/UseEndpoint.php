<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Endpoints\UseStatementInserter;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;

class UseEndpoint extends EndpointProvider
{
    public function use($value = null)
    {
        if($this->file->directive('add')) return $this->add($value);

        if($value === null) return $this->get();

        return $this->set($value);
    }

    protected function get()
    {
        return collect((new NodeFinder)->findInstanceOf($this->ast(), Use_::class))
            ->map(function($useStatement) {
                return collect($useStatement->uses)->map(function($useStatement) {
                    $base = join('\\', $useStatement->name->parts); 
                    return $base . ($useStatement->alias ? ' as ' . $useStatement->alias : '');
                });
            })->flatten()->toArray();
    }

    public function set($newUseStatements)
    {
        $traverser = new NodeTraverser();
        $visitor = new UseStatementInserter($this->ast(), $newUseStatements);
        $traverser->addVisitor($visitor);

        $this->file->ast = $traverser->traverse($this->ast());

        return $this->file->continue();
    }    

    public function add($newUseStatements)
    {
        $namespace = (new NodeFinder)->findFirstInstanceOf($this->ast(), Namespace_::class);
        $traverser = new NodeTraverser();
        $visitor = new UseStatementInserter(
            $namespace ? $this->ast()[0]->stmts : $this->ast(),
            $newUseStatements);
        $traverser->addVisitor($visitor);

        $this->file->ast($traverser->traverse(
            $namespace ? $this->ast()[0]->stmts : $this->ast()
        ));

        return $this->file->continue();
    }    
}