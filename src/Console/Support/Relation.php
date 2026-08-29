<?php

namespace Archetype\Console\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Builds the source of an Eloquent relationship method.
 *
 * The library's own relationship endpoints cover four types and emit the
 * argument-free form, which means any relation with a pivot table or a
 * non-conventional key has to be hand-edited immediately after being generated.
 * Generating the source here instead covers the whole matrix and lets the extra
 * arguments be part of the same operation.
 */
class Relation
{
    /** Relation type => [needs a related class, is a to-many relation, needs a morph name] */
    const TYPES = [
        'hasOne' => [true, false, false],
        'hasMany' => [true, true, false],
        'belongsTo' => [true, false, false],
        'belongsToMany' => [true, true, false],
        'hasOneThrough' => [true, false, false],
        'hasManyThrough' => [true, true, false],
        'morphOne' => [true, false, true],
        'morphMany' => [true, true, true],
        'morphTo' => [false, false, false],
        'morphToMany' => [true, true, true],
        'morphedByMany' => [true, true, true],
    ];

    /**
     * Positional arguments each type accepts after the ones it requires.
     *
     * PHP has no way to skip a positional argument, so these are only appended
     * while they are contiguous — a gap is rejected rather than filled with
     * nulls the caller did not ask for.
     */
    const OPTIONS = [
        'hasOne' => ['foreign-key', 'local-key'],
        'hasMany' => ['foreign-key', 'local-key'],
        'belongsTo' => ['foreign-key', 'owner-key'],
        'belongsToMany' => ['table', 'foreign-key', 'related-key'],
        'hasOneThrough' => ['first-key', 'second-key'],
        'hasManyThrough' => ['first-key', 'second-key'],
        'morphOne' => ['type-column', 'id-column'],
        'morphMany' => ['type-column', 'id-column'],
        'morphTo' => ['type-column', 'id-column'],
        'morphToMany' => ['table'],
        'morphedByMany' => ['table'],
    ];

    public function __construct(
        protected string $type,
        protected ?string $related,
        protected array $options = [],
    ) {
        if (! array_key_exists($type, self::TYPES)) {
            throw new InvalidArgumentException(
                "unknown relation type '$type' — one of ".implode(', ', array_keys(self::TYPES))
            );
        }

        [$needsRelated, , $needsMorphName] = self::TYPES[$type];

        if ($needsRelated && ! $related) {
            throw new InvalidArgumentException("$type needs a related class");
        }

        if ($needsMorphName && ! $this->option('morph-name')) {
            throw new InvalidArgumentException("$type needs --morph-name (the polymorphic name, e.g. commentable)");
        }

        if (str_ends_with($type, 'Through') && ! $this->option('through')) {
            throw new InvalidArgumentException("$type needs --through (the intermediate model)");
        }
    }

    public function name(): string
    {
        if ($given = $this->option('name')) {
            return $given;
        }

        if ($this->type === 'morphTo') {
            return Str::camel($this->option('morph-name') ?: 'related');
        }

        $base = class_basename($this->related);

        return Str::camel(self::TYPES[$this->type][1] ? Str::plural($base) : $base);
    }

    /** Fully qualified names this method needs imported. */
    public function imports(): array
    {
        return array_values(array_filter([
            $this->related,
            $this->option('through'),
            $this->option('using'),
        ]));
    }

    public function source(): string
    {
        $name = $this->name();

        return implode(PHP_EOL, [
            '/**',
            ' * Get the associated '.$this->docBlockName(),
            ' */',
            'public function '.$name.'()',
            '{',
            '    return $this->'.$this->type.'('.implode(', ', $this->arguments()).')'.$this->chain().';',
            '}',
        ]);
    }

    /** @return array<int, string> */
    protected function arguments(): array
    {
        $arguments = [];

        if ($this->related) {
            $arguments[] = class_basename($this->related).'::class';
        }

        if (str_ends_with($this->type, 'Through')) {
            $arguments[] = class_basename($this->option('through')).'::class';
        }

        if (self::TYPES[$this->type][2] || ($this->type === 'morphTo' && $this->option('morph-name'))) {
            $arguments[] = $this->quote($this->option('morph-name'));
        }

        return array_merge($arguments, $this->optionalArguments());
    }

    /** @return array<int, string> */
    protected function optionalArguments(): array
    {
        $given = [];
        $seenGap = false;

        foreach (self::OPTIONS[$this->type] as $option) {
            $value = $this->option($option);

            if ($value === null) {
                $seenGap = true;

                continue;
            }

            if ($seenGap) {
                throw new InvalidArgumentException(
                    "--$option cannot be given without the arguments before it: "
                    .implode(', ', array_map(fn ($o) => "--$o", self::OPTIONS[$this->type]))
                );
            }

            $given[] = $this->quote($value);
        }

        return $given;
    }

    protected function chain(): string
    {
        $chain = '';

        if ($using = $this->option('using')) {
            $chain .= '->using('.class_basename($using).'::class)';
        }

        if ($pivot = $this->option('with-pivot')) {
            $columns = collect(explode(',', $pivot))
                ->map(fn ($column) => $this->quote(trim($column)))
                ->join(', ');

            $chain .= '->withPivot('.$columns.')';
        }

        if ($this->option('with-timestamps')) {
            $chain .= '->withTimestamps()';
        }

        return $chain;
    }

    protected function docBlockName(): string
    {
        if ($this->type === 'morphTo') {
            return Str::studly($this->option('morph-name') ?: 'related');
        }

        $base = class_basename($this->related);

        return Str::studly(self::TYPES[$this->type][1] ? Str::plural($base) : $base);
    }

    protected function option(string $key)
    {
        $value = $this->options[$key] ?? null;

        return $value === '' ? null : $value;
    }

    protected function quote(string $value): string
    {
        return "'".str_replace("'", "\\'", $value)."'";
    }
}
