<?php

use Archetype\Support\AST\ASTQueryBuilder;

test('it can extract call chains', function() {
    $code = <<< CODE
    str(string: 'Quick Example')
        ->lower()
        ->before(search: 'example')
        ->trim();
    CODE;
    
    PHPFile::fromString($code)
        ->astQuery()
        ->where(fn($query) => $query
            ->methodCall()->where('name->name', str)
            ->methodCall()->where('name->name', lower)
            ->methodCall()->where('name->name', before)
            ->methodCall()->where('name->name', trim)
            ->get()
        )->get();
    
    PHPFile::fromString($code)
        ->astQuery()
        ->callChainStartingAt(function($query) {

        })
        ->staticCallChainStartingAt(function($query) {

        })        
});