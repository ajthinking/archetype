<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile as File;
use RuntimeException;

/**
 * `LaravelFile`'s model property endpoints — `fillable()`, `casts()`, `table()`
 * and the rest — one command each, named after the endpoint.
 *
 * They are one class because they are one endpoint underneath: `ModelProperties`
 * turns every one of them into `assumeType(...)->protected()->property(...)`.
 * The name is a constructor argument so the service provider can register them
 * without ten near-identical files.
 */
class ModelPropertyCommand extends EndpointCommand
{
    /** endpoint => the type it assumes, mirroring ModelProperties */
    const PROPERTIES = [
        'casts' => 'array',
        'connection' => 'string',
        'table' => 'string',
        'dates' => 'array',
        'timestamps' => 'boolean',
        'visible' => 'array',
        'guarded' => 'array',
        'unguarded' => 'array',
        'fillable' => 'array',
        'hidden' => 'array',
    ];

    public function __construct(protected string $property = 'fillable')
    {
        $this->signature = "archetype:$this->property
            {target : ".self::TARGET_DESCRIPTION."}
            {value? : The value, as JSON when it is not a plain string. Omit to read it}";

        $this->description = "Read or write \$$this->property on an Eloquent model";

        parent::__construct();
    }

    protected function directives(): array
    {
        return ['add', 'remove', 'clear', 'empty'];
    }

    protected function hasValue(): bool
    {
        return $this->argument('value') !== null;
    }

    protected function get(File $file)
    {
        return $file->{$this->property}();
    }

    protected function set(File $file)
    {
        $this->requireKind($file, ['class']);
        $this->guardAgainstTheOtherMechanism($file);

        $raw = $this->argument('value');

        if ($outcome = $this->alreadyDone($file, $raw)) {
            return $outcome;
        }

        $raw === null
            ? $this->withDirectives($file)->{$this->property}()
            : $this->withDirectives($file)->{$this->property}(Code::value($raw));

        return "\$$this->property ".$this->verb();
    }

    /**
     * Laravel 11 generates `protected function casts(): array`, and `getCasts()`
     * merges it with the `$casts` property. Writing the property beside an
     * existing method is honoured by the merge and still leaves a model with two
     * casting mechanisms, which no reviewer would accept — so say where the
     * change belongs instead of quietly making that mess.
     */
    protected function guardAgainstTheOtherMechanism(File $file): void
    {
        if ($this->property !== 'casts' || ! (new Introspector($file))->method('casts')) {
            return;
        }

        throw new RuntimeException(
            'this model declares a casts() method, so writing $casts would leave it with two '
            .'casting mechanisms — use archetype:set-array-key <target> casts <key> <value> instead'
        );
    }

    /**
     * @return array<string, mixed>|null
     *
     * Read off the syntax tree, not through the endpoint: the directives are
     * already on the file here, so the endpoint would treat a read as a write.
     */
    protected function alreadyDone(File $file, ?string $raw): ?array
    {
        $current = collect((new Introspector($file))->properties())->firstWhere('name', $this->property);

        if (($this->option('remove') || $this->option('empty')) && ! $current) {
            return $this->unchanged("no \$$this->property");
        }

        if ($this->option('add') && $current && is_array($current['value']) && $raw !== null) {
            return array_diff((array) Code::value($raw), $current['value'])
                ? null
                : $this->unchanged("\$$this->property unchanged");
        }

        return null;
    }

    protected function verb(): string
    {
        return match (true) {
            (bool) $this->option('remove') => 'removed',
            (bool) $this->option('empty') => 'emptied',
            (bool) $this->option('clear') => 'cleared',
            (bool) $this->option('add') => 'added to',
            default => 'set',
        };
    }
}
