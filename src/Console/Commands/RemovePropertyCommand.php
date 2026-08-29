<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile;

class RemovePropertyCommand extends MutationCommand
{
    protected $signature = 'archetype:remove-property
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Property name, without the $}';

    protected $description = 'Remove a class property';

    protected function perform(): int
    {
        $name = $this->argument('name');

        return $this->mutate(function (LaravelFile $file) use ($name) {
            if (! (new Introspector($file))->hasProperty($name)) {
                return $this->unchanged("no \$$name");
            }

            $file->remove()->property($name);

            return "\$$name removed";
        });
    }
}
