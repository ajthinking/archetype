<?php

namespace Archetype\Schema\Strategies;

use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Archetype\Schema\Types\EnumType;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FromDatabase
{
    public static function get()
    {
        $models = app('LaravelFile')::models()->get()->map(function ($model) {
            return 'App\\' . $model->className(); // TODO !!!
        })->values();

        $entities = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        return (object) [
            'entities' => $models->map(function ($model) {
                return (object) [
                    'model' => $model,
                    'table' => static::getTable($model),
                    'columns' => static::getColumns($model),
                ];
            })->values()->toArray(),
            'strategy_used' => static::class
        ];
    }

    public static function getColumns($model)
    {
        $model = app($model);
        
        $table = $model->getConnection()->getTablePrefix() . $model->getTable();
        $schema = $model->getConnection()->getDoctrineSchemaManager($table);

        $database = null;
        if (strpos($table, '.')) {
            list($database, $table) = explode('.', $table);
        }

        $columns = $schema->listTableColumns($table, $database);

        $columns = collect($columns)->map(function ($column) use ($model) {
            return $column->toArray();
        });

        return $columns->toArray();
    }

    public static function getTable($model)
    {
        $model = app($model);
        return $model->getConnection()->getTablePrefix() . $model->getTable();
    }
}
