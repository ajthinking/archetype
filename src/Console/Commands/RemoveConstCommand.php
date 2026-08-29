<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile;

class RemoveConstCommand extends MutationCommand
{
    protected $signature = 'archetype:remove-const
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Constant name}';

    protected $description = 'Remove a class constant';

    protected function perform(): int
    {
        $name = $this->argument('name');

        return $this->mutate(function (LaravelFile $file) use ($name) {
            $present = collect((new Introspector($file))->constants())
                ->contains(fn ($constant) => $constant['name'] === $name);

            if (! $present) {
                return $this->unchanged("no const $name");
            }

            $file->remove()->classConstant($name);

            return "const $name removed";
        });
    }
}
