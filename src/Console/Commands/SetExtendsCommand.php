<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\LaravelFile;

class SetExtendsCommand extends MutationCommand
{
    protected $signature = 'archetype:set-extends
        {target : '.self::TARGET_DESCRIPTION.'}
        {parent : The parent class, fully qualified to have the import added too}';

    protected $description = 'Set the parent class, importing it when needed';

    protected function perform(): int
    {
        $parent = $this->argument('parent');

        return $this->mutate(function (LaravelFile $file) use ($parent) {
            $this->requireKind($file, ['class']);

            if ($file->extends() === class_basename($parent)) {
                return $this->unchanged('extends unchanged');
            }

            $imported = $this->import($file, [$parent]);

            $file->extends(class_basename($parent));

            return 'extends '.class_basename($parent).($imported ? ' (+use)' : '');
        });
    }
}
