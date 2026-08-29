<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\LaravelFile;

class SetNamespaceCommand extends MutationCommand
{
    protected $signature = 'archetype:set-namespace
        {target : '.self::TARGET_DESCRIPTION.'}
        {namespace : The new namespace}';

    protected $description = 'Set the namespace of a file';

    protected function perform(): int
    {
        $namespace = $this->argument('namespace');

        return $this->mutate(function (LaravelFile $file) use ($namespace) {
            if ((string) $file->namespace() === $namespace) {
                return $this->unchanged('namespace unchanged');
            }

            $file->namespace($namespace);

            return "namespace $namespace";
        });
    }
}
