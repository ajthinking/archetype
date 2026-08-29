<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile;

class EmptyPropertyCommand extends MutationCommand
{
    protected $signature = 'archetype:empty-property
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Property name, without the $}';

    protected $description = 'Empty a class property, keeping the declaration';

    protected function perform(): int
    {
        $name = $this->argument('name');

        return $this->mutate(function (LaravelFile $file) use ($name) {
            $this->requirePropertyHolder($file);

            if (! (new Introspector($file))->hasProperty($name)) {
                return $this->unchanged("no \$$name");
            }

            $file->{$this->visibilityOf($file, $name)}()->empty()->property($name);

            return "\$$name emptied";
        });
    }
}
