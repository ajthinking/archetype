<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Member;
use Archetype\Console\Support\Relation;
use Archetype\LaravelFile as File;

/**
 * Eloquent relationship methods, one command per relation type.
 *
 * `hasOne`, `hasMany`, `belongsTo` and `belongsToMany` are `LaravelFile`
 * endpoints, and with no options given this calls them, so
 * `archetype hasMany <file> Task` and `$file->hasMany('Task')` produce the same
 * method. The remaining seven types have no endpoint, and every type accepts
 * options the endpoints cannot express — a pivot table, explicit keys — in
 * which case the method is generated here instead.
 */
class RelationCommand extends MutationCommand
{
    /** The four that exist as LaravelFile endpoints. */
    const ENDPOINTS = ['hasOne', 'hasMany', 'belongsTo', 'belongsToMany'];

    public function __construct(protected string $type = 'hasMany')
    {
        $this->signature = "archetype:$this->type
            {target : ".self::TARGET_DESCRIPTION."}
            {related? : The related class}
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
            {--with-timestamps : Add withTimestamps() to a pivot relation}
            {--no-import : Do not import the related class}";

        $this->description = "Add a $this->type relationship method";

        parent::__construct();
    }

    protected function perform(): int
    {
        $relation = new Relation($this->type, $this->argument('related'), $this->relationOptions());

        $name = $relation->name();

        // With nothing but a related class to go on, the endpoint is the
        // authority: this is then literally `$file->hasMany('Task')`.
        $useEndpoint = in_array($this->type, self::ENDPOINTS, true)
            && ! array_filter($this->relationOptions());

        $method = $useEndpoint ? null : Code::method($relation->source());

        return $this->mutate(function (File $file) use ($relation, $name, $method, $useEndpoint) {
            $this->requireKind($file, ['class']);

            if (in_array($name, $file->methodNames(), true)) {
                return $this->unchanged("$name exists");
            }

            $imported = $this->option('no-import') ? 0 : $this->import($file, $relation->imports());

            $useEndpoint
                ? $file->{$this->type}($this->argument('related'))
                : Member::add($file, Code::copy($method));

            return sprintf('%s %s%s', $this->type, $name, $imported ? " (+$imported use)" : '');
        });
    }

    /** @return array<string, mixed> */
    protected function relationOptions(): array
    {
        return [
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
        ];
    }
}
