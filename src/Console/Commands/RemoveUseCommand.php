<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\LaravelFile;

class RemoveUseCommand extends MutationCommand
{
    protected $signature = 'archetype:remove-use
        {target : '.self::TARGET_DESCRIPTION.'}
        {imports* : Fully qualified names to stop importing}';

    protected $description = 'Remove import statements';

    protected function perform(): int
    {
        $imports = $this->argument('imports');

        return $this->mutate(function (LaravelFile $file) use ($imports) {
            $existing = $file->use();
            $keep = array_values(array_diff($existing, $imports));

            if (count($keep) === count($existing)) {
                return $this->unchanged('imports unchanged');
            }

            $file->use($keep);

            return 'import -'.(count($existing) - count($keep));
        });
    }
}
