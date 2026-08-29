<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\LaravelFile;

class AddTraitCommand extends MutationCommand
{
    protected $signature = 'archetype:add-trait
        {target : '.self::TARGET_DESCRIPTION.'}
        {traits* : Trait names, fully qualified to have the import added too}';

    protected $description = 'Use a trait in a class, importing it when needed';

    protected function perform(): int
    {
        $traits = $this->argument('traits');

        return $this->mutate(function (LaravelFile $file) use ($traits) {
            $existing = array_map(fn ($trait) => class_basename($trait), $file->useTrait());

            $wanted = array_values(array_filter(
                $traits,
                fn ($trait) => ! in_array(class_basename($trait), $existing, true)
            ));

            if (! $wanted) {
                return $this->unchanged('traits unchanged');
            }

            $imported = $this->import($file, $wanted);

            $file->add()->useTrait(array_map(fn ($trait) => class_basename($trait), $wanted));

            return 'uses +'.count($wanted).($imported ? " (+$imported use)" : '');
        });
    }
}
