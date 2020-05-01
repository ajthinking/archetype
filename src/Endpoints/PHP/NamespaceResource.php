<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\ResourceEndpointProvider;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeFinder;
use PhpParser\BuilderFactory;

class NamespaceResource extends ResourceEndpointProvider
{
    const aliases = ['namespace'];

    public function get()
    {
        $namespace = (new NodeFinder)->findFirstInstanceOf($this->ast(), Namespace_::class);
        return $namespace ? implode('\\', $namespace->name->parts) : null;
    }

    public function set($newNamespace)
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

    public function remove($_ = null)
    {
        $namespace = (new NodeFinder)->findFirstInstanceOf($this->ast(), Namespace_::class);
        
        if($namespace) {
            $this->file->ast($namespace->stmts);
        }

        return $this->file->continue();
    }    
}