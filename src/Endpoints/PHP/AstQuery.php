<?php

namespace Archetype\Endpoints\PHP;

use Illuminate\Support\Str;
use Archetype\Support\AST\ASTQueryBuilder;
use Archetype\Endpoints\EndpointProvider;

class AstQuery extends EndpointProvider
{
    public function astQuery() {
        return ASTQueryBuilder::fromFile($this->file);
    }
}