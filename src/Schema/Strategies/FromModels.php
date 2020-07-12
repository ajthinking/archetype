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

class FromModels
{
    public static function get()
    {
        $models = app('LaravelFile')::models()->get()->map(function($model) {
            return 'App\\' . $model->className(); // TODO !!!
        })->values();

        return (object) [
            'entities' => $models->map(function($model) {
                return (object) [
                    'model' => $model,
                    'columns' => [],
                ];
            })->values()->toArray(),
            'strategy' => static::class
        ];
    }
}