<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Support\AST\ASTQueryBuilder;
use Archetype\Endpoints\EndpointProvider;

class AstQuery extends EndpointProvider
{
    /**
     * @example Get a AstQueryBuilder instance
     * @source $file->astQuery()
     *
     * @return Archetype\Support\AST\ASTQueryBuilder
     */
    public function astQuery()
    {
		$builderClass = $this->file->astQueryBuilder;

        // Create AST builder instance
        $builder = new $builderClass($this->file->ast());
        
        // Attach the file so we can return it later
        $builder->parent = $this->file;
        
        return $builder;
    }
}
