<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\LaravelFile as File;

/** `$file->namespace()`, `$file->namespace($value)` and `$file->remove()->namespace()`. */
class NamespaceCommand extends EndpointCommand
{
    protected $signature = 'archetype:namespace
        {target : '.self::TARGET_DESCRIPTION.'}
        {value? : The new namespace. Omit to read it}';

    protected $description = 'Read, set or remove the namespace of a file';

    protected function directives(): array
    {
        return ['remove'];
    }

    protected function hasValue(): bool
    {
        return $this->argument('value') !== null;
    }

    protected function get(File $file)
    {
        return (string) $file->namespace();
    }

    protected function set(File $file)
    {
        $value = $this->argument('value');

        if ($this->option('remove')) {
            if ((string) $file->namespace() === '') {
                return $this->unchanged('no namespace');
            }

            $file->remove()->namespace();

            return 'namespace removed';
        }

        if ((string) $file->namespace() === $value) {
            return $this->unchanged('namespace unchanged');
        }

        $file->namespace($value);

        return "namespace $value";
    }
}
