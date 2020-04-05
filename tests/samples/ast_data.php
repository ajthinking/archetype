<?php

function first() {
    $data = "first function";
    return $data;
};

function second() {
    $data = "second function";
    return $data;
};

/*

use PHPFileManipulator\Support\AST\ASTQueryBuilder;

use PHPFileManipulator\Support\AST\Survivor;

$ast = LaravelFile::user()->ast();

$query = new ASTQueryBuilder($ast);

$query
    ->class()
    ->method()
    ->named('cool')
    ->get();

$result = LaravelFile::load(
    'database/migrations/2014_10_12_000000_create_users_table.php'
)
    ->astQuery() // get a ASTQueryBuilder
    ->method()
    ->named('up')
    ->staticCall()
    ->where('class', 'Schema')
    ->named('create')
    ->remember('table_name', function ($node) {
        return $node
            ->arg(0)
            ->value
            ->value
            ->get()
            ->first();
    })
    ->arg(1)
    ->value
    ->stmts()
    ->methodCall()
    ->where('var->name', 'table')
    ->remember('column_type', function ($node) {
        return $node
            ->name()
            ->name()
            ->get()
            ->first();
    })
    ->args
    ->value
    ->value
    ->remember('column_name', function ($node) {
        return $node->get();
    })
    ->recall();

$result;



*/