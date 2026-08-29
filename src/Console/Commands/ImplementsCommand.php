<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\LaravelFile as File;
use Symfony\Component\Console\Input\InputOption;

/**
 * `$file->implements()`, `$file->implements($names)` and
 * `$file->add()->implements($names)`.
 *
 * Given a fully qualified name it also adds the import, because an interface
 * named without one is never valid PHP. `--no-import` leaves that to you.
 */
class ImplementsCommand extends EndpointCommand
{
    protected $signature = 'archetype:implements
        {target : '.self::TARGET_DESCRIPTION.'}
        {names?* : Interface names, fully qualified to have the import added too. Omit to read them}';

    protected $description = 'Read or set the interfaces a class implements';

    protected function directives(): array
    {
        return ['add'];
    }

    protected function hasValue(): bool
    {
        return (bool) $this->argument('names');
    }

    protected function get(File $file)
    {
        return $file->implements();
    }

    protected function set(File $file)
    {
        $this->requireKind($file, ['class']);

        $names = $this->argument('names');
        $short = fn ($name) => class_basename($name);

        if (! $this->option('add')) {
            $imported = $this->option('no-import') ? 0 : $this->import($file, $names);

            $file->implements(array_map($short, $names));

            return 'implements set to '.count($names).($imported ? " (+$imported use)" : '');
        }

        $existing = array_map($short, $file->implements());
        $wanted = array_values(array_filter($names, fn ($name) => ! in_array($short($name), $existing, true)));

        if (! $wanted) {
            return $this->unchanged('implements unchanged');
        }

        $imported = $this->option('no-import') ? 0 : $this->import($file, $wanted);

        $file->add()->implements(array_map($short, $wanted));

        return 'implements +'.count($wanted).($imported ? " (+$imported use)" : '');
    }

    /** @return array<int, InputOption> */
    protected function sharedOptions(): array
    {
        return array_merge(parent::sharedOptions(), [
            new InputOption('no-import', null, InputOption::VALUE_NONE, 'Do not import the interfaces'),
        ]);
    }
}
