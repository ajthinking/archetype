<?php

namespace PHPFileManipulator\Endpoints\PHP;

use Illuminate\Support\Str;
use PHPFileManipulator\Support\AST\ASTQueryBuilder;
use PHPFileManipulator\Endpoints\EndpointProvider;

class AstQuery extends EndpointProvider
{
    public function astQuery() {
        return ASTQueryBuilder::fromFile($this->file);
    }
}