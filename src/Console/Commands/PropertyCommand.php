<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Directives;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile as File;
use Archetype\Support\Types;
use Symfony\Component\Console\Input\InputOption;

/**
 * `$file->property($name)` and `$file->property($name, $value)`, with the
 * directives that endpoint honours as flags.
 */
class PropertyCommand extends EndpointCommand
{
    protected $signature = 'archetype:property
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Property name, without the $}
        {value? : The value, as JSON when it is not a plain string. Omit to read it}';

    protected $description = 'Read or write a class property';

    protected function directives(): array
    {
        return array_merge(Directives::WRITING, Directives::VISIBILITY, ['static']);
    }

    protected function hasValue(): bool
    {
        return $this->argument('value') !== null;
    }

    protected function get(File $file)
    {
        return $file->property($this->name());
    }

    protected function set(File $file)
    {
        $this->requireKind($file, ['class']);

        $name = $this->name();
        $raw = $this->argument('value');

        if ($outcome = $this->alreadyDone($file, $name, $raw)) {
            return $outcome;
        }

        // The endpoint reads `add`, `remove`, `empty` and `clear` off the file,
        // so by the time this runs the directives are already on it and the
        // value is all that is left to hand over. `--clear` with no value is
        // how the API declares a property without a default.
        $this->withDirectives($this->withVisibility($file, $name))
            ->property($name, $raw === null ? Types::NO_VALUE : Code::value($raw));

        return $this->describe($name);
    }

    protected function name(): string
    {
        return ltrim($this->argument('name'), '$');
    }

    /**
     * The property endpoint rewrites the modifiers on every set, defaulting to
     * public, so saying nothing about visibility would quietly widen a
     * protected property. Only an explicit flag changes it.
     */
    protected function withVisibility(File $file, string $name): File
    {
        foreach (Directives::VISIBILITY as $flag) {
            if ($this->option($flag)) {
                return $file;
            }
        }

        foreach ((new Introspector($file))->properties() as $property) {
            if ($property['name'] === $name) {
                return $file->{$property['visibility']}();
            }
        }

        return $file->protected();
    }

    /**
     * @return array<string, mixed>|null the unchanged marker, when there is nothing to do
     *
     * The current value is read off the syntax tree rather than through
     * `$file->property()`, because by this point the directives are already on
     * the file and the endpoint would treat the read as another write.
     */
    protected function alreadyDone(File $file, string $name, ?string $raw): ?array
    {
        $current = collect((new Introspector($file))->properties())->firstWhere('name', $name);

        // `--clear` on a property that is not there declares it, which is how
        // the API writes one with no default, so it is not nothing to do.
        if (($this->option('remove') || $this->option('empty')) && ! $current) {
            return $this->unchanged("no \$$name");
        }

        if ($this->option('add') && $current && is_array($current['value'])) {
            return array_diff((array) Code::value($raw), $current['value'])
                ? null
                : $this->unchanged("\$$name unchanged");
        }

        return null;
    }

    protected function describe(string $name): string
    {
        return match (true) {
            (bool) $this->option('remove') => "\$$name removed",
            (bool) $this->option('empty') => "\$$name emptied",
            (bool) $this->option('clear') => "\$$name cleared",
            (bool) $this->option('add') => "\$$name added to",
            default => "\$$name set",
        };
    }

    /** @return array<int, InputOption> */
    protected function sharedOptions(): array
    {
        return array_merge(parent::sharedOptions(), [
            new InputOption('assume-type', null, InputOption::VALUE_REQUIRED, 'Type to assume when the property does not exist yet, e.g. array'),
        ]);
    }
}
