<?php

namespace Archetype\Commands\Demo;

use Illuminate\Support\Str;
use Archetype\Facades\LaravelFile;
use ReflectionProperty;

class Project
{
    public $schema;

    public $models;

    public function __construct()
    {
        //$this->schema = $this->makeSchema();
    }

    public function missingRelationshipMethods()
    {
        return collect([1, 2, 3]);
    }

    protected function makeSchema()
    {
        $this->models = LaravelFile::models()->get()->map(function ($model) {
            $model->foreignColumns = $this->findForeignColumns($model);
            return $model;
        });
    }

    protected function findForeignColumns($model)
    {
        // Assume table name from class name
        $tableName = Str::snake(
            Str::plural($model->className())
        );

        // Assume only one migration
        $migration = LaravelFile::in('database/migrations')
            ->where('className', 'like', $tableName)
            ->get()
            ->first();

        // Find all columns having a foreign key constraint
        $data = $migration->astQuery()
            ->method()
            ->named('up')
            ->staticCall()
                ->where('class', 'Schema')
                ->named('create')
            ->args
            ->closure()
            ->stmts()
            ->methodCall()
                ->where('var->name', 'table')
            ->args
            ->value
            ->value
            ->get();

        // Result
        dd($data);
    }
}
