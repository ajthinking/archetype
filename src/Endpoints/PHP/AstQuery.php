<?php

namespace PHPFileManipulator\Endpoints\PHP;

use Illuminate\Support\Str;
use PHPFileManipulator\Support\AST\ASTQueryBuilder;
use PHPFileManipulator\Support\EndpointProvider;

class AstQuery extends EndpointProvider
{
    public function getHandlerMethod($signature, $args)
    {
        return $signature == 'astQuery' ? 'astQuery' : false;
    }
    
    public function astQuery() {
        return new ASTQueryBuilder($this->file->ast());
    }
}