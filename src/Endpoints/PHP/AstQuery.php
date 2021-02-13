<?php

namespace Archetype\Endpoints\PHP;

use Illuminate\Support\Str;
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
        // Create AST builder instance
        $builder = new ASTQueryBuilder($this->file->ast());
        
        // Attach the file so we can return it later
        $builder->file = $this->file;
        
        return $builder;
    }
}
