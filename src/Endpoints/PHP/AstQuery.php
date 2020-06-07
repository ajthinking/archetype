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
    public function astQuery() {
        return ASTQueryBuilder::fromFile($this->file);
    }
}