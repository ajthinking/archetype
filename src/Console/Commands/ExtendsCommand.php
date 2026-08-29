<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\LaravelFile as File;
use Symfony\Component\Console\Input\InputOption;

/** `$file->extends()` and `$file->extends($name)`. */
class ExtendsCommand extends EndpointCommand
{
    protected $signature = 'archetype:extends
        {target : '.self::TARGET_DESCRIPTION.'}
        {name? : The parent class. Omit to read it}';

    protected $description = 'Read or set the parent class';

    protected function directives(): array
    {
        return [];
    }

    protected function hasValue(): bool
    {
        return $this->argument('name') !== null;
    }

    protected function get(File $file)
    {
        return $file->extends();
    }

    protected function set(File $file)
    {
        $this->requireKind($file, ['class']);

        $parent = $this->argument('name');

        if ($file->extends() === class_basename($parent)) {
            return $this->unchanged('extends unchanged');
        }

        $imported = $this->option('no-import') ? 0 : $this->import($file, [$parent]);

        $file->extends(class_basename($parent));

        return 'extends '.class_basename($parent).($imported ? ' (+use)' : '');
    }

    /** @return array<int, InputOption> */
    protected function sharedOptions(): array
    {
        return array_merge(parent::sharedOptions(), [
            new InputOption('no-import', null, InputOption::VALUE_NONE, 'Do not import the parent class'),
        ]);
    }
}
