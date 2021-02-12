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

class FromFiles
{
    public static function get()
    {
        $models = app('LaravelFile')::models()->get()->map(function ($model) {
            return 'App\\' . $model->className(); // TODO !!!
        })->values();

        return (object) [
            'entities' => $models->map(function ($model) {
                return (object) [
                    'model' => $model,
                    'columns' => static::getColumnsFromMigrations($model),
                ];
            })->values()->toArray(),
            'strategy_used' => static::class
        ];
    }

    protected static function getColumnsFromMigrations($model)
    {
        return [];

        $models = app('LaravelFile')::models()->get();
        $migrations = app('LaravelFile')::migrations()->get();

        $table = $model->table() ?? Str::snake($model->className());

        return app('LaravelFile')::in('database/migrations')->get()->map(function ($file) {
            return (object) [
                'name' => $file->className()
            ];
        })->toArray();
    }
}
