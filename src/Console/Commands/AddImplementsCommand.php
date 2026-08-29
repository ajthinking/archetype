<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\LaravelFile;

class AddImplementsCommand extends MutationCommand
{
    protected $signature = 'archetype:add-implements
        {target : '.self::TARGET_DESCRIPTION.'}
        {interfaces* : Interface names, fully qualified to have the import added too}';

    protected $description = 'Add interfaces to a class, importing them when needed';

    protected function perform(): int
    {
        $interfaces = $this->argument('interfaces');

        return $this->mutate(function (LaravelFile $file) use ($interfaces) {
            $existing = array_map(fn ($name) => class_basename($name), $file->implements());

            $wanted = array_values(array_filter(
                $interfaces,
                fn ($name) => ! in_array(class_basename($name), $existing, true)
            ));

            if (! $wanted) {
                return $this->unchanged('implements unchanged');
            }

            $imported = $this->import($file, $wanted);

            $file->add()->implements(array_map(fn ($name) => class_basename($name), $wanted));

            return 'implements +'.count($wanted).($imported ? " (+$imported use)" : '');
        });
    }
}
