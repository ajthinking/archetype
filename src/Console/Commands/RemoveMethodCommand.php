<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\LaravelFile;

class RemoveMethodCommand extends MutationCommand
{
    protected $signature = 'archetype:remove-method
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : The method to remove}';

    protected $description = 'Remove a method';

    protected function perform(): int
    {
        $name = $this->argument('name');

        return $this->mutate(function (LaravelFile $file) use ($name) {
            if (! in_array($name, $file->methodNames(), true)) {
                return $this->unchanged("no fn $name");
            }

            $file->astQuery()
                ->classMethod()
                ->where('name->name', $name)
                ->remove()
                ->commit()
                ->end();

            return "fn $name removed";
        });
    }
}
