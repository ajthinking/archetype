<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\LaravelFile as File;
use Symfony\Component\Console\Input\InputOption;

/**
 * `$file->useTrait()`, `$file->useTrait($names)` and
 * `$file->add()->useTrait($names)`.
 *
 * Given a fully qualified name it also adds the import, because a trait used
 * without one is never valid PHP. `--no-import` leaves that to you.
 */
class UseTraitCommand extends EndpointCommand
{
    protected $signature = 'archetype:useTrait
        {target : '.self::TARGET_DESCRIPTION.'}
        {names?* : Trait names, fully qualified to have the import added too. Omit to read them}';

    protected $description = 'Read or set the traits a class uses';

    protected function directives(): array
    {
        return ['add'];
    }

    protected function hasValue(): bool
    {
        return (bool) $this->argument('names');
    }

    /**
     * The endpoint answers with `PhpParser\Node\Name` objects, which is right
     * for PHP and useless on a command line, so they are printed as the names
     * they stand for.
     */
    protected function get(File $file)
    {
        return array_map(fn ($name) => (string) $name, $file->useTrait());
    }

    protected function set(File $file)
    {
        $this->requireKind($file, ['class']);

        $names = $this->argument('names');
        $short = fn ($name) => class_basename($name);

        if (! $this->option('add')) {
            $imported = $this->option('no-import') ? 0 : $this->import($file, $names);

            $file->useTrait(array_map($short, $names));

            return 'uses set to '.count($names).($imported ? " (+$imported use)" : '');
        }

        $existing = array_map($short, $this->get($file));
        $wanted = array_values(array_filter($names, fn ($name) => ! in_array($short($name), $existing, true)));

        if (! $wanted) {
            return $this->unchanged('traits unchanged');
        }

        $imported = $this->option('no-import') ? 0 : $this->import($file, $wanted);

        $file->add()->useTrait(array_map($short, $wanted));

        return 'uses +'.count($wanted).($imported ? " (+$imported use)" : '');
    }

    /** @return array<int, InputOption> */
    protected function sharedOptions(): array
    {
        return array_merge(parent::sharedOptions(), [
            new InputOption('no-import', null, InputOption::VALUE_NONE, 'Do not import the traits'),
        ]);
    }
}
