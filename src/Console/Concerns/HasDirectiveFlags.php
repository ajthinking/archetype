<?php

namespace Archetype\Console\Concerns;

use Archetype\Console\Support\Directives;
use Archetype\LaravelFile as File;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

/**
 * The PHP API's directives, as command line flags.
 *
 * `$file->add()->property('fillable', 'nickname')` becomes
 * `archetype property <file> fillable nickname --add`. The flags are named
 * after the directive methods so there is one vocabulary to learn, not two,
 * and a command declares only the ones its endpoint actually honours.
 */
trait HasDirectiveFlags
{
    /** Which directives this command's endpoint honours. */
    abstract protected function directives(): array;

    /** Apply the flags the caller gave to the file, in the order the API would. */
    protected function withDirectives(File $file): File
    {
        foreach ($this->directives() as $directive) {
            if ($this->option($directive)) {
                $file = $file->{$directive}();
            }
        }

        if ($this->hasOption('assume-type') && $type = $this->option('assume-type')) {
            $file = $file->assumeType($type);
        }

        return $file;
    }

    /** True when the caller asked a question rather than for a change. */
    protected function isRead(): bool
    {
        foreach (array_intersect($this->directives(), Directives::WRITING) as $directive) {
            if ($this->option($directive)) {
                return false;
            }
        }

        return ! $this->hasValue();
    }

    /** Reject flag combinations the endpoint cannot act on together. */
    protected function guardDirectives(): void
    {
        $given = array_values(array_filter(
            array_intersect($this->directives(), Directives::WRITING),
            fn ($directive) => $this->option($directive)
        ));

        if (count($given) > 1) {
            throw new InvalidArgumentException(
                'only one of '.implode(', ', array_map(fn ($d) => "--$d", $given)).' at a time'
            );
        }

        $visibility = array_values(array_filter(
            Directives::VISIBILITY,
            fn ($flag) => in_array($flag, $this->directives(), true) && $this->option($flag)
        ));

        if (count($visibility) > 1) {
            throw new InvalidArgumentException(
                'only one of '.implode(', ', array_map(fn ($f) => "--$f", $visibility)).' at a time'
            );
        }
    }

    /** @return array<int, InputOption> */
    protected function directiveOptions(): array
    {
        $options = [];

        foreach ($this->directives() as $directive) {
            $options[] = new InputOption(
                $directive,
                null,
                InputOption::VALUE_NONE,
                Directives::ALL[$directive]
            );
        }

        return $options;
    }
}
