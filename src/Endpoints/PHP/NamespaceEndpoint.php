<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeFinder;
use PhpParser\BuilderFactory;

class NamespaceEndpoint extends EndpointProvider
{
    public function namespace($value = null)
    {
        if($this->file->directive('remove')) return $this->remove();

        if($value === null) return $this->get();

        return $this->set($value);
    }

    protected function get()
    {
        $namespace = (new NodeFinder)->findFirstInstanceOf($this->ast(), Namespace_::class);
        return $namespace ? implode('\\', $namespace->name->parts) : null;
    }

    protected function set($newNamespace)
    {
        $namespace = (new NodeFinder)->findFirstInstanceOf($this->ast(), Namespace_::class);
        
        if($namespace) {
            // Modifying existing namespace
            $namespace->name->parts = explode("\\", $newNamespace);
        } else {
            // Add a namespace
            $ast = $this->file->ast();
            array_unshift(
                $ast,
                (new BuilderFactory)->namespace($newNamespace)->getNode()
            );

            $this->file->ast($ast);
        }
        
        return $this->file->continue();
    }

    protected function remove()
    {
        $namespace = (new NodeFinder)->findFirstInstanceOf($this->ast(), Namespace_::class);
        
        if($namespace) {
            $this->file->ast($namespace->stmts);
        }

        return $this->file->continue();
    }    
}