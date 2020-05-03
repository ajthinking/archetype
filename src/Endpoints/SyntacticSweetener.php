<?php

namespace PHPFileManipulator\Endpoints;

use Illuminate\Support\Str;
use PHPFileManipulator\Support\AST\ASTQueryBuilder;
use PHPFileManipulator\Endpoints\EndpointProvider;

class SyntacticSweetener extends EndpointProvider
{
    protected $words = [
        'a',
        'also',
        'and',
        'epic',
        'from',
        'have',
        'it',
        'make',
        'please',
        'should',
        'thanks',
        'then',
        'to',
    ];

    public function getHandlerMethod($signature, $args)
    {
        return collect($this->words)->contains($signature) ? $signature : false;
    }
    
    public function __call($method, $args)
    {
        // Do nothing :)
        return $this->file;
    }
}