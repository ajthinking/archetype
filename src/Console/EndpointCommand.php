<?php

namespace Archetype\Console;

use Archetype\Console\Concerns\HasDirectiveFlags;
use Archetype\Facades\LaravelFile;
use Archetype\LaravelFile as File;
use Archetype\Console\Support\Target;
use Symfony\Component\Console\Input\InputOption;

/**
 * A command that is one of the PHP API's endpoints.
 *
 * The command is named after the endpoint and behaves the way the endpoint
 * does: given a value it writes, given none it reads, and the directives are
 * flags. `$file->add()->property('fillable', 'nickname')` and
 * `archetype property <file> fillable nickname --add` are the same call.
 */
abstract class EndpointCommand extends MutationCommand
{
    use HasDirectiveFlags;

    /** Read the endpoint. Return the value. */
    abstract protected function get(File $file);

    /** Write the endpoint. Return a short description of what changed. */
    abstract protected function set(File $file);

    /** Whether the caller supplied something to write. */
    abstract protected function hasValue(): bool;

    protected function perform(): int
    {
        $this->guardDirectives();

        // Directives are applied at the write itself, not here. They live on
        // the file, and the endpoints read them off it — so a file carrying
        // `add` would treat a read taken along the way as another write.
        return $this->isRead()
            ? $this->readEach(fn (File $file) => $this->get($this->withDirectives($file)))
            : $this->mutate(fn (File $file) => $this->set($file));
    }

    /**
     * Answer the same question for every target.
     *
     * A single file answers with the bare value, so it can be used in a script
     * without trimming anything off. A directory answers with one `path value`
     * line per file, because otherwise the values would not say what they
     * belong to.
     */
    protected function readEach(callable $read): int
    {
        $targets = $this->targets();
        $single = count($targets) === 1 && ! Target::isDirectory($this->argument('target'));
        $values = [];

        foreach ($targets as $path) {
            $value = $read(LaravelFile::load($path));
            $values[$path] = $value;

            $this->emit(trim(($single ? '' : $path.' ').$this->present($value)));
        }

        $this->payload = $single
            ? ['file' => array_key_first($values), 'value' => reset($values)]
            : ['values' => $values, 'count' => count($values)];

        return self::SUCCESS;
    }

    /** Scalars raw so they can be piped; anything structured as compact JSON. */
    protected function present($value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return 'null';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /** @return array<int, InputOption> */
    protected function sharedOptions(): array
    {
        return array_merge(parent::sharedOptions(), $this->directiveOptions());
    }
}
