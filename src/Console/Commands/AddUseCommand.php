<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\LaravelFile;

class AddUseCommand extends MutationCommand
{
    protected $signature = 'archetype:add-use
        {target : '.self::TARGET_DESCRIPTION.'}
        {imports* : Fully qualified names, optionally "Name as Alias"}';

    protected $description = 'Add import statements';

    protected function perform(): int
    {
        $imports = $this->argument('imports');

        return $this->mutate(function (LaravelFile $file) use ($imports) {
            $missing = array_values(array_diff($imports, $file->use()));

            if (! $missing) {
                return $this->unchanged('imports unchanged');
            }

            $file->add()->use($missing);

            return 'import +'.count($missing);
        });
    }
}
