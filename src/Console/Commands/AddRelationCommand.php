<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Member;
use Archetype\Console\Support\Relation;
use Archetype\LaravelFile;

class AddRelationCommand extends MutationCommand
{
    protected $signature = 'archetype:add-relation
        {target : '.self::TARGET_DESCRIPTION.'}
        {type : hasOne, hasMany, belongsTo, belongsToMany, hasOneThrough, hasManyThrough, morphOne, morphMany, morphTo, morphToMany or morphedByMany}
        {related? : The related class, required for every type except morphTo}
        {--name= : Method name, defaulting to the conventional one}
        {--morph-name= : The polymorphic name, e.g. commentable}
        {--through= : The intermediate model, for the through relations}
        {--table= : Pivot table}
        {--foreign-key= : Foreign key, or the foreign pivot key for belongsToMany}
        {--related-key= : Related pivot key, for belongsToMany}
        {--local-key= : Local key}
        {--owner-key= : Owner key, for belongsTo}
        {--first-key= : First key, for the through relations}
        {--second-key= : Second key, for the through relations}
        {--type-column= : Morph type column}
        {--id-column= : Morph id column}
        {--using= : Custom pivot model}
        {--with-pivot= : Comma separated pivot columns}
        {--with-timestamps : Add withTimestamps() to a pivot relation}';

    protected $description = 'Add an Eloquent relationship method';

    protected function perform(): int
    {
        $relation = new Relation(
            $this->argument('type'),
            $this->argument('related'),
            [
                'name' => $this->option('name'),
                'morph-name' => $this->option('morph-name'),
                'through' => $this->option('through'),
                'table' => $this->option('table'),
                'foreign-key' => $this->option('foreign-key'),
                'related-key' => $this->option('related-key'),
                'local-key' => $this->option('local-key'),
                'owner-key' => $this->option('owner-key'),
                'first-key' => $this->option('first-key'),
                'second-key' => $this->option('second-key'),
                'type-column' => $this->option('type-column'),
                'id-column' => $this->option('id-column'),
                'using' => $this->option('using'),
                'with-pivot' => $this->option('with-pivot'),
                'with-timestamps' => $this->option('with-timestamps'),
            ]
        );

        $name = $relation->name();
        $method = Code::method($relation->source());

        return $this->mutate(function (LaravelFile $file) use ($relation, $name, $method) {
            if (in_array($name, $file->methodNames(), true)) {
                return $this->unchanged("$name exists");
            }

            $imported = $this->import($file, $relation->imports());

            Member::add($file, Code::copy($method));

            return sprintf(
                '%s %s%s',
                $this->argument('type'),
                $name,
                $imported ? " (+$imported use)" : ''
            );
        });
    }
}
