<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\LaravelFile as File;

/**
 * `$file->use()`, `$file->use($names)` and `$file->add()->use($names)`.
 *
 * Without `--add` this replaces the import list wholesale, exactly as the
 * endpoint does.
 */
class UseCommand extends EndpointCommand
{
    protected $signature = 'archetype:use
        {target : '.self::TARGET_DESCRIPTION.'}
        {names?* : Fully qualified names, optionally "Name as Alias". Omit to read them}';

    protected $description = 'Read or set the import statements of a file';

    protected function directives(): array
    {
        return ['add'];
    }

    protected function hasValue(): bool
    {
        return (bool) $this->argument('names');
    }

    protected function get(File $file)
    {
        return $file->use();
    }

    protected function set(File $file)
    {
        $names = $this->argument('names');
        $existing = $file->use();

        if ($this->option('add')) {
            $missing = array_values(array_diff($names, $existing));

            if (! $missing) {
                return $this->unchanged('imports unchanged');
            }

            $file->add()->use($missing);

            return 'import +'.count($missing);
        }

        if ($existing === $names) {
            return $this->unchanged('imports unchanged');
        }

        $file->use($names);

        return 'imports set to '.count($names);
    }
}
