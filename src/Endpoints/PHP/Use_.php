<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\BuilderFactory;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_ as PhpParserUse_;
use PhpParser\NodeTraverser;
use Arr;

class Use_ extends EndpointProvider
{
    public function use($value = null)
    {
        if($this->file->directive('add')) return $this->add($value);

        if($value === null) return $this->get();
        
        return $this->set($value);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->use()
            ->uses
            ->get()
            ->map(function($useStatement) {
                $base = join('\\', $useStatement->name->parts); 
                return $base . ($useStatement->alias ? ' as ' . $useStatement->alias : '');
            });
    }

    protected function set($newUseStatements)
    {
        return $this->add($newUseStatements);
    }

    protected function add($newUseStatements)
    {
        collect(Arr::wrap($newUseStatements))->each(function($name) {
            $this->file->astQuery()
            ->insertStmt(
                (new BuilderFactory)->use($name)->getNode()
            )
            ->commit();
        });

        return $this->file->continue();
    }    
}