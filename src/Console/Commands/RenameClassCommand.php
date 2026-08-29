<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile;

/**
 * Renames the declaration only. References elsewhere in the project, and the
 * file name itself, are left alone — a caller that wants those moved should say
 * so explicitly rather than have a rename quietly become a refactor.
 */
class RenameClassCommand extends MutationCommand
{
    protected $signature = 'archetype:rename-class
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : The new class name}';

    protected $description = 'Rename the class declared in a file';

    protected function perform(): int
    {
        $name = $this->argument('name');

        return $this->mutate(function (LaravelFile $file) use ($name) {
            if ((new Introspector($file))->name() === $name) {
                return $this->unchanged('class name unchanged');
            }

            $file->className($name);

            return "class $name";
        });
    }
}
